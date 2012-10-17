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
 

// PAYMENT METHOD MOLLIE
// *********************

// SETTINGS - USED BY BACKEND
$MOD_BAKERY[$payment_method]['TXT_PARTNER_ID'] = 'Mollie Partner ID';

// USED BY FILE bakery/payment_methods/mollie/processor.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'Mollie (iDEAL)';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'iDEAL allows you to make online purchases in a familiar, secure and simple way. If you already have online banking, you can start using iDEAL right away.';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'Select your bank.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Learn more about buying safely on the iDEAL security page';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'You will be redirected to the familiar and secure online banking page of your bank where you authorise the payment in the usual way.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'After completion of the transaction our order confirmation will be emailed to you and your bank will confirm your payment.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'iDEAL Website';
$MOD_BAKERY[$payment_method]['TXT_SELECT_BANK'] = 'Select a bank';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'I will pay with iDEAL';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'To handle the payment processing you will be redirected to the secure server of your online bank.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Go to iDEAL now';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Total incl. tax and shipping';
$MOD_BAKERY[$payment_method]['ERROR_CREATING_PM'] = 'An error has occurred. Your payment could not be initialized.';
$MOD_BAKERY[$payment_method]['ERROR_NO_BANK_SELECTED'] = 'An error has occurred. Please select a bank.';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Thank you for your online payment! Your transaction has been completed.<br />Our order confirmation has been emailed to you.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'We will ship your order as soon as possible.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'A problem has occurred. The transaction has not been completed.<br />Please contact the shop admin.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'You have canceled your payment with iDEAL.<br />Do you like to continue shopping?';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation for your order at [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Dear [CUSTOMER_NAME]

Thank you for shopping at [SHOP_NAME].
Please find below the information about the products you have ordered:
[ITEM_LIST]

We will ship the order to the address below:

[ADDRESS]


Thank you for the confidence you have placed in us.

Kind regards,
[SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'New order at [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Dear [SHOP_NAME] Administrator

NEW ORDER AT [SHOP_NAME]:
	Order #: [ORDER_ID]
	Payment method: Mollie (iDEAL)

Shipping address:
[ADDRESS]

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