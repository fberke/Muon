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
 

// Check vars
$get_transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : "";

// Get transaction id and status from db
$query_customers = $database->query("SELECT transaction_id, transaction_status FROM ".TABLE_PREFIX."mod_bakery_customer WHERE order_id = '{$_SESSION['bakery']['order_id']}'");
if ($query_customers->numRows() > 0) {
	$customer = $query_customers->fetchRow();
	$transaction_id = stripslashes($customer['transaction_id']);
	$transaction_status = stripslashes($customer['transaction_status']);
} else {
	return;
}

// Check if the payment has been canceled by user
if ($get_transaction_id == $transaction_id && $transaction_status != 'paid') {
	$payment_status = "canceled";	
	return;
}

// Check if the payment has been completed successfull
elseif ($get_transaction_id == $transaction_id && $transaction_status == 'paid') {
	$payment_status = "success";	
	return;
}

// Check if there has been an error during payment processing
else {
	$payment_status = "error";
	return;
}
?>