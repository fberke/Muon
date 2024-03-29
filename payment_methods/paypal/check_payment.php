<?php


// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php'); 
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) { 
			include($dir.'/framework/class.secure.php'); $inc = true;	break; 
		} 
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include class.secure.php
 

// Use PayPal PDT (Payment Data Transfer)

// Sample code provided by PayPal as a starting point
// https://www.paypaltech.com/PDTGen/generate_pdt.php


// Testing
$testing = false;  // Use testing mode for detailed success / error messages
$sandbox = false;  // Use paypal sandbox



// Initialize or set vars
$header   = '';
$errors   = array();
$keyarray = array();

// Check GET vars
$tx_token   = isset($_GET['tx'])     ? strip_tags($_GET['tx'])     : '';
$get_status = isset($_GET['status']) ? strip_tags($_GET['status']) : '';

// Check if the payment has been canceled by user
if ($get_status == 'canceled') {
	$payment_status = 'canceled';	
	return;
}

// Get SESSION vars
$order_id    = $_SESSION['bakery']['order_id'];
$order_total = $_SESSION['bakery']['order_total'];

// Get PayPal email (business var) and authentication token from db
$query_payment_methods = $database->query("SELECT value_1, value_3 FROM ".TABLE_PREFIX."mod_bakery_payment_methods WHERE directory = 'paypal'");
if ($query_payment_methods->numRows() > 0) {
	$payment_methods  = $query_payment_methods->fetchRow();
	$setting_business = stripslashes($payment_methods['value_1']);
	$auth_token       = stripslashes($payment_methods['value_3']);
}

// Get payment type (submitted as), transaction id, transaction status and payment status from db
$query_customers = $database->query("SELECT submitted, transaction_id, transaction_status, status FROM ".TABLE_PREFIX."mod_bakery_customer WHERE order_id = '$order_id'");
if ($query_customers->numRows() > 0) {
	$customer = $query_customers->fetchRow();
	$submitted          = stripslashes($customer['submitted']);
	$transaction_id     = stripslashes($customer['transaction_id']);
	$transaction_status = stripslashes($customer['transaction_status']);
	$status             = stripslashes($customer['status']);
}

// Read the post from PayPal and add 'cmd' var
$req  = 'cmd=_notify-synch';
$req .= "&tx=$tx_token&at=$auth_token";

// Post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

$pdt_url = $sandbox ? 'www.sandbox.paypal.com' : 'www.paypal.com';

$fp = fsockopen($pdt_url, 80, $errno, $errstr, 30);


// Process validation from PayPal
if (!$fp) {
	// HTTP error
	if ($testing) {
		echo '<h2>PayPal Payment Data Transfer (PDT)</h2>';
		echo '<p>The PayPal payment with order id <b>' . $order_id . '</b> and transaction id <b>' . $tx_token . '</b>';
		echo ' could not be verified by Bakery.</p>';
		echo '<p><b>ERROR: Unable to connect to PayPal PDT server</b> ('.$errno.': '.$errstr.').</p>';
		echo '<p>To see all the transaction details, please log in to your PayPal account.</p>';
	}
	$payment_status = "error";
	return;
}
else {
	fputs ($fp, $header . $req);
	// Read the body data
	$res = '';
	$headerdone = false;
	while (!feof($fp)) {
		$line = fgets($fp, 1024);
		if (strcmp($line, "\r\n") == 0) {
			// Read the header
			$headerdone = true;
		}
		elseif ($headerdone) {
			// Header has been read, now read the contents
			$res .= $line;
		}
	}

	// Parse the data
	$lines = explode("\n", $res);

	// Prepare testing message
	$testing_msg    = '<h2>PayPal Payment Data Transfer (PDT)</h2>';
	$testing_msg   .= 'The PayPal payment with order id <b>' . $order_id . '</b> ';
	
	$testing_msg_2  = 'To see all the transaction details, please log in to your PayPal account.<br /><br />';
	$testing_msg_2 .= 'Find further information on this transaction below:<br />';
	$testing_msg_2 .= nl2br(urldecode($res)) . '<br />';

	// Only make checkings if not verified yet 
	if ($submitted == 'no' || $transaction_status != 'paid') {

		// SUCCESS
		if (strcmp($lines[0], "SUCCESS") == 0) {
			for ($i = 1; $i < count($lines) - 1; $i++) {
				list($key, $val) = explode("=", $lines[$i]);
				$keyarray[urldecode($key)] = urldecode($val);
			}
	
			// Confirm that the payment status is Completed
			if ($keyarray['payment_status'] != 'Completed') {
				$errors[] = 'The payment status returned by PayPal is "' . $keyarray['payment_status'] . '".';
				$errors[] = 'The payment status should be "Completed".';
			}
			// Verify that the transaction has not already been processed
			if ($keyarray['txn_id'] == $transaction_id) {
				$errors[] = 'The transaction has already been processed.';
			}
			// Validate if the receiver�s email address is registered to Bakery
			if ($keyarray['business'] != $setting_business) {
				$errors[] = 'The receiver�s PayPal email address (business var) is not registered to Bakery.';
			}
			// Check if the order id is correct
			if ($keyarray['invoice'] != $order_id) {
				$errors[] = 'The order id did not match.';
			}
			// Check if the payment amount is correct
			if ($keyarray['mc_gross'] != $order_total) {
				$errors[] = 'The payment amount did not match.';
			}
	
			// If no errors occured set payment status to successfull
			if (count($errors) == 0) {
				if ($testing) {
					$testing_msg .= 'has been completed successfull.<br />';
					$testing_msg .= $testing_msg_2;
					echo $testing_msg;
				}

				// Set payment status success and update db
				$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_customer SET transaction_status = 'paid', transaction_id = '$tx_token' WHERE order_id = '$order_id'");
				$payment_status = "success";
				return;
			}

			// ERROR
			else {
				if ($testing) {
					$testing_msg .= 'has not been completed.<br /><br />';
					$testing_msg .= '<b>Please see the list below for transaction-specific details:</b><br />';
					foreach ($errors as $value) {
						$testing_msg .= ' - ' . $value . '<br />';
					}
					$testing_msg .= '<br /><br />';
					$testing_msg .= $testing_msg_2;
					echo $testing_msg;
				}

				// Set payment status pending and update db
				$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_customer SET transaction_status = 'pending', transaction_id = '$tx_token' WHERE order_id = '$order_id'");
				$payment_status = "pending";
				return;
			}
		}

		// FAIL
		elseif (strcmp($lines[0], "FAIL") == 0) {
			if ($testing) {
				$testing_msg .= 'is invalid and has not been completed.<br />';
				$testing_msg .= $testing_msg_2;
				echo $testing_msg;
			}

			// Set payment status pending and update db
			$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_customer SET transaction_status = 'pending' WHERE order_id = '$order_id'");
			$payment_status = "pending";
			return;
		}
	}

	// Payment already completed successfully (by IPN)
	else {

		if ($testing) {
			$testing_msg .= 'has already been completed successfully (by IPN).<br />';
			$testing_msg .= 'Transaction has already been saved in data base.<br /><br />';
			$testing_msg .= $testing_msg_2;
			echo $testing_msg;
		}

		$payment_status = "success";
		$email_sent     = true;
		return;
	}
}

fclose ($fp);

?>