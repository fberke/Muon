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
 

// Include WB config.php file, WB admin class and ideal class
require('../../../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
require_once('ideal.class.php');



// CREATE PAYMENT AND REDIRECT TO BANK
// ***********************************

// Create ideal payment using ideal class
if (isset($_POST['bank_id']) AND !empty($_POST['bank_id']) AND isset($_POST['payment_method']) AND $_POST['payment_method'] == 'mollie') {

	$payment_method = $_POST['payment_method'];
	$partner_id = $_SESSION['bakery'][$payment_method]['partner_id'];
	$bank_id = $_POST['bank_id'];
	$amount = $_SESSION['bakery'][$payment_method]['amount'];
	$description = $_SESSION['bakery'][$payment_method]['description'];
	$return_url = $_SESSION['bakery'][$payment_method]['return_url'];
	$report_url = $_SESSION['bakery'][$payment_method]['report_url'];
	$order_id = $_SESSION['bakery']['order_id'];

	// Process payment
	$iDEAL = new iDEAL_Payment ($partner_id);
	if ($iDEAL->createPayment($bank_id, $amount, $description, $return_url, $report_url)) {
	
		// Update transaction_id in customer table 
		$transaction_id = $iDEAL->getTransactionId();
		$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_customer SET transaction_id = '$transaction_id' WHERE order_id = '$order_id'");
		// Send customer to the bank payment page
		header('location: ' . $iDEAL->getBankURL());
	} else {
		header('location: '.$_POST['setting_continue_url'].'?pay_error=1');
	}
} else {
	header('location: '.$_POST['setting_continue_url'].'?pay_error=2');
}