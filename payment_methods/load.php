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
 

// GET INFO OF AVAIABLE PAYMENT METHODS AND PAYMENT GATEWAYS
$avaiable_payment_methods = array();
$load_payment_methods = array();

// Load all payment methods/gateways
$directory = WB_PATH.'/modules/bakery/payment_methods';
// Open the payment_methods directory then loop through its contents
$dir = dir($directory);
while (false !== $payment_method_dir = $dir->read()) {
	// Skip index file and pointers
	if (strpos($payment_method_dir, '.php') !== false || substr($payment_method_dir, 0, 1) == ".") {
		continue;
	}
	// Make array of all avaiable payment method directories
	$avaiable_payment_methods[] = $payment_method_dir;
}

// Check if the latest version of avaiable payment methods are in database 
foreach ($avaiable_payment_methods as $payment_method_dir) {
	$query_payment_methods = $database->query("SELECT version FROM ".TABLE_PREFIX."mod_bakery_payment_methods WHERE directory = '$payment_method_dir'");
	if ($query_payment_methods->numRows() > 0) {
		$fetch_payment_method = $query_payment_methods->fetchRow();
		$old_version = stripslashes($fetch_payment_method['version']);
		// Get payment method info file
		$info_file_path = "$directory/$payment_method_dir/info.php";
		if (is_file($info_file_path)) {
			require($info_file_path);
		}
		// Compare payment method old version (in db) with latest version
		if ($old_version >= $payment_method_version) {
			continue;
		} else {
			// Payment method obsolete => add it to the array of payment methods that should be upgraded 
			$load_payment_methods[] = $payment_method_dir;
		}
	} else {
		// Payment method not installed yet => add it to the array of payment methods that should be loaded 
		$load_payment_methods[] = $payment_method_dir;
	}
}


// Insert new payment method or update payment method
if (!empty($load_payment_methods)) {
	foreach ($load_payment_methods as $payment_method_dir) {

		// Get payment method info file
		$info_file_path = "$directory/$payment_method_dir/info.php";
		if (is_file($info_file_path)) {
			require($info_file_path);
		}
		// Look for payment method language file
		$payment_method = $payment_method_dir;
		if (LANGUAGE_LOADED) {
			include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/EN.php');
			if (file_exists(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php')) {
				include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php');
			}
		}

		// Get payment method table to see if we have to install or upgrade the payment method
		$query_payment_methods = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_payment_methods WHERE directory = '$payment_method_dir'");
		$payment_method = $query_payment_methods->fetchRow();
		
		if ($query_payment_methods->numRows() > 0) {

			// Upgrade payment method (mysql update)
			$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_payment_methods SET name = '$payment_method_name', version = '$payment_method_version', author = '$payment_method_author', requires = '$requires_bakery_module', field_1 = '$field_1', field_2 = '$field_2', field_3 = '$field_3', field_4 = '$field_4', field_5 = '$field_5', field_6 = '$field_6' WHERE directory = '$payment_method_dir'");

			// Include upgrade.php if exists
			if (file_exists(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/upgrade.php')) {
				include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/upgrade.php');
			}

		} else {
			// Install payment method (mysql insert)
			// Prepare email vars
			$cust_email_subject = $MOD_BAKERY[$payment_method_dir]['EMAIL_SUBJECT_CUSTOMER'];
			$cust_email_body = $MOD_BAKERY[$payment_method_dir]['EMAIL_BODY_CUSTOMER'];
			$shop_email_subject = $MOD_BAKERY[$payment_method_dir]['EMAIL_SUBJECT_SHOP'];
			$shop_email_body = $MOD_BAKERY[$payment_method_dir]['EMAIL_BODY_SHOP'];

			// Insert with invoice template (value_4)
			if ($payment_method_dir == "invoice") {
				$value_4 = $MOD_BAKERY[$payment_method_dir]['INVOICE_TEMPLATE'];
				$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_payment_methods (active, directory, name, version, author, requires, field_1, field_2, field_3, field_4, value_4, field_5, field_6, cust_email_subject, cust_email_body, shop_email_subject, shop_email_body) VALUES ('0', '$payment_method_dir', '$payment_method_name', '$payment_method_version', '$payment_method_author', '$requires_bakery_module', '$field_1', '$field_2', '$field_3', '$field_4', '$value_4', '$field_5', '$field_6', '$cust_email_subject', '$cust_email_body', '$shop_email_subject', '$shop_email_body')");
			} else {
				// Insert without invoice template
				$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_payment_methods (active, directory, name, version, author, requires, field_1, field_2, field_3, field_4, field_5, field_6, cust_email_subject, cust_email_body, shop_email_subject, shop_email_body) VALUES ('0', '$payment_method_dir', '$payment_method_name', '$payment_method_version', '$payment_method_author', '$requires_bakery_module', '$field_1', '$field_2', '$field_3', '$field_4', '$field_5', '$field_6', '$cust_email_subject', '$cust_email_body', '$shop_email_subject', '$shop_email_body')");
			}

			// Include install.php if exists
			if (file_exists(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/install.php')) {
				include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/install.php');
			}
		}
	}
}

?>