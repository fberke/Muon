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
 

// PAYMENT METHOD PAYPAL
// *********************

// SETTINGS - USED BY BACKEND
$MOD_BAKERY[$payment_method]['TXT_EMAIL'] = 'PayPal Email';
$MOD_BAKERY[$payment_method]['TXT_PAGE'] = 'PayPal Page';
$MOD_BAKERY[$payment_method]['TXT_AUTH_TOKEN'] = 'PDT Identity Token';

$MOD_BAKERY[$payment_method]['TXT_NOTICE'] = '
<b>Website Payment Preferences</b><br />
Log in to your PayPal account: Go to &quot;My Account&quot; &gt; &quot;Profile&quot; &gt; &quot;Website Payment Preferences&quot;.<br />

<b>Auto Return:</b> Click the &quot;Auto Return&quot; radio button <i>on</i>.<br />
<b>Return URL:</b> Enter the url as shown below to the field &quot;Return URL&quot;:<input type="text" value="' . WB_URL . '" readonly="true" onclick="this.select();" style="width: 98%;" />

<b>Payment Data Transfer:</b> Click the &quot;Payment Data Transfer (PDT)&quot; radio button <i>on</i> and then click <i>Save</i>.<br />
A confirmation message will appear at the top of the page indicating that you have successfully enabled &quot;Payment Data Transfer&quot;. Your identity token will appear within that message, as well as below the PDT on/off radio buttons. Copy&amp;paste your identity token to the textfield right above this box.<br /><br />

<b>Instant Payment Notification Preferences</b><br />
Go to &quot;My Account&quot; &gt; &quot;Profile&quot; &gt; &quot;Instant Payment Notification Preferences&quot;.<br />
Click the &quot;Edit IPN Settings&quot; button and you will be taken to the configuration page.<br />
Copy&amp;paste the full url as shown below to the field &quot;Notification URL&quot;:<input type="text" value="' . WB_URL . '/modules/bakery/payment_methods/paypal/ipn.php" readonly="true" onclick="this.select();" style="width: 98%;" />
Click the &quot;Receive IPN messages&quot; radio button <i>on</i> and save your changes.<br />';

// USED BY FILE bakery/payment_methods/paypal/processor.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'Credit card (PayPal)';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'Pay online with PayPal using your credit card: easy, safe, free...';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'Pay your order online using your credit card or PayPal payment.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Learn more about buying safely on the PayPal Security Center page';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'The payment processing is handled by the secure PayPal server.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'After completion of the transaction, our order confirmation and a PayPal receipt for your purchase will be emailed to you.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'PayPal Website';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'I will pay with PayPal';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'To handle the payment processing you will be redirected to a secure PayPal server.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Go to PayPal now';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Total incl. tax and shipping';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Thank you for your online payment! Your transaction has been completed.<br />Our order confirmation and a PayPal receipt for your purchase has been emailed to you.';
$MOD_BAKERY[$payment_method]['TXT_PENDING'] = 'Thank you for your online payment! Your transaction will be processed shortly.<br />Our order confirmation and a PayPal receipt for your purchase will be emailed to you.';
$MOD_BAKERY[$payment_method]['TXT_TRANSACTION_STATUS'] = "PLEASE NOTE:\n\tThe transaction status is \"PENDING\".\n\tTo see all the transaction details, please log in to your PayPal account.";
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'We will ship your order as soon as possible.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'A problem has occurred. The transaction has not been completed.<br />Please contact the shop admin.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'You have canceled your PayPal payment.<br />Do you like to continue shopping?';

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
	Payment method: PayPal
[TRANSACTION_STATUS]

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