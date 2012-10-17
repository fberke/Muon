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
 

// PAYMENT METHOD INVOICE
// **********************

// SETTINGS - USED BY BACKEND
$MOD_BAKERY[$payment_method]['TXT_BANK_ACCOUNT'] = 'Shop Bank Account';
$MOD_BAKERY[$payment_method]['TXT_INVOICE_TEMPLATE'] = 'Invoice Template';
$MOD_BAKERY[$payment_method]['TXT_INVOICE_ALERT'] = '1. Reminder Alert after';
$MOD_BAKERY[$payment_method]['TXT_REMINDER_ALERT'] = '2. Reminder Alert after';

// USED BY FILE bakery/payment_methods/invoice/processor.php
$MOD_BAKERY[$payment_method]['TXT_INVOICE_PAYMENT'] = 'Invoice';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'Please pay the balance due to our bank account in compliance with the term of payment.';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Charge to my Account';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'We will email you an order confirmation.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'We will ship your order as soon as possible.';

// INVOICE TEMPLATE
$MOD_BAKERY[$payment_method]['INVOICE_TEMPLATE'] = '<img src="[WB_URL]/modules/bakery/images/logo.gif" width="690" height="75" alt="[SHOP_NAME] Logo" class="mod_bakery_logo_b" />
<br />
<p class="mod_bakery_shop_address_b">[SHOP_NAME] | Company | No Street | City ZIP | COUNTRY</p>
<br /><br /><br />
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_INVOICE]">[CUST_ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_DELIVERY_NOTE]">[ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_REMINDER]">[CUST_ADDRESS]</p>
<br /><br /><br /><br /><br /><br />
<h2>[TITLE]</h2>
<p class="mod_bakery_invoice_no_b">Order Date: [ORDER_DATE]<br />
Order#: [ORDER_ID] | [CURRENT_DATE]</p>
<br />
[ITEM_LIST]
<br /><br /><br />

<div style="display: [DISPLAY_INVOICE]">
<p class="mod_bakery_thank_you_b">Thank you for shopping at [SHOP_NAME].</p>
<p class="mod_bakery_pay_invoice_b">Please pay the balance due within 30 days into account below:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<div style="display: [DISPLAY_DELIVERY_NOTE]">
<p class="mod_bakery_thank_you_b">Thank you for shopping at [SHOP_NAME].</p>
</div>

<div style="display: [DISPLAY_REMINDER]">
<p class="mod_bakery_pay_invoice_b">Please disregard this letter if you have already made payment. Otherwise please pay the balance due within 10 days into account below:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<br /><br />';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation for your order at [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Dear [CUSTOMER_NAME]

Thank you for shopping at [SHOP_NAME].
Please find below the information about the products you have ordered:
[ITEM_LIST]

We will ship the order to the address below:

[ADDRESS]

We will send you the invoice to the address below:

[CUST_ADDRESS]


Thank you for the confidence you have placed in us.

Kind regards,
[SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'New order at [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Dear [SHOP_NAME] Administrator

NEW ORDER AT [SHOP_NAME]:
	Order #: [ORDER_ID]
	Payment method: Invoice

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