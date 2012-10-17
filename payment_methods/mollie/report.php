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

// Get the payment method settings from db
$query_payment_methods = $database->query("SELECT value_1 FROM ".TABLE_PREFIX."mod_bakery_payment_methods WHERE directory = 'mollie'");
if ($query_payment_methods->numRows() > 0) {
	$payment_methods = $query_payment_methods->fetchRow();
	$partner_id = stripslashes($payment_methods['value_1']);  // Mollie partner id
}

// Check if payment is completed
if (isset($_GET['transaction_id'])) {
	$transaction_id = $_GET['transaction_id'];
	$iDEAL = new iDEAL_Payment ($partner_id);
	$iDEAL->checkPayment($transaction_id);

	// If payment status is payed write it into db
	if ($iDEAL->getPaidStatus() == true) {
		$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_customer SET transaction_status = 'paid' WHERE transaction_id = '$transaction_id'");
	}
}
?>