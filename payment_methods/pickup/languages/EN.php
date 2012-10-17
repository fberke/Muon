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
 


// PAYMENT METHOD PICKUP
// *********************

// SETTINGS - USED BY BACKEND


// USED BY FILE bakery/payment_methods/pickup/processor.php
$MOD_BAKERY[$payment_method]['TXT_PICKUP_PAYMENT'] = 'Pickup';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'I pay when I pickup my order';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Submit order';

// USED BY FILE bakery/view.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'We will email you an order confirmation and the pickup address. Furthermore, we will phone you to arrange a date for the pickup.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'You can check your order at pickup and pay cash.';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation and information for your order at [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Dear [CUSTOMER_NAME]

Thank you for shopping at [SHOP_NAME].
Please find below the information about the products you have ordered:
[ITEM_LIST]

You can check your order at pickup and pay cash.

Pickup address:
NAME/FIRMA
STRASSE
PLZ ORT

Please note: we will phone you to arrange a date for the pickup.

Thank you for the confidence you have placed in us.

Kind regards,
[SHOP_NAME]

';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'New order at [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Dear [SHOP_NAME] Administrator

NEW ORDER AT [SHOP_NAME]:
	Order #: [ORDER_ID]
	Payment method: Pickup

Invoice address:
[CUST_ADDRESS]

List of ordered items: 
[ITEM_LIST]


Kind regards,
[SHOP_NAME]

';



// If utf-8 is set as default charset convert some iso-8859-1 strings to utf-8 
if (defined('DEFAULT_CHARSET') && DEFAULT_CHARSET == 'utf-8') {
	$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = utf8_encode($MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER']);
	$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = utf8_encode($MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER']);
	$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = utf8_encode($MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP']);
	$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = utf8_encode($MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP']);
}

?>
