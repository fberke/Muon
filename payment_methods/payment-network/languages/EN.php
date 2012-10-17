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
 

// PAYMENT METHOD PAYMENT-NETWORK
// ******************************

// Get the current url scheme
$url = parse_url(WB_URL);

// SETTINGS - USED BY BACKEND
$MOD_BAKERY[$payment_method]['TXT_USER_ID'] = 'Customer no';
$MOD_BAKERY[$payment_method]['TXT_PROJECT_ID'] = 'Project no';
$MOD_BAKERY[$payment_method]['TXT_PROJECT_PW'] = 'Project password';
$MOD_BAKERY[$payment_method]['TXT_NOTIFICATION_PW'] = 'Notification password';
$MOD_BAKERY[$payment_method]['TXT_NOTICE'] = "
<b>DIRECTebanking.com Extended settings</b><br />
Log in to your <a href='https://www.sofortueberweisung.de/payment/users/login' target='_blank'>DIRECTebanking.com</a> account: Go to &quot;My projects&quot; &gt; &quot;Select a project&quot; &gt; &quot;Extended settings&quot;<br /><br />

<b>Shop system interface:</b> Activate &quot;Automatic redirection&quot; and copy&amp;paste the full url as shown below to the field &quot;Success link&quot;:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&transaction_id=-TRANSACTION-' readonly='true' onclick='this.select();' style='width: 98%;' />

Copy&amp;paste the full url as shown below to the field &quot;Abort link&quot;:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&amp;status=canceled' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Notifications:</b> Add an email notification <u>and</u> a HTTP notification using the <i>POST</i>-methode and the full url as shown below:<input type='text' value='".WB_URL."/modules/bakery/payment_methods/payment-network/report.php' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Project password, notification password and input check:</b> Create a project password and a notification password <u>and</u> activate the input check using the hash algorithm <i>SHA1</i>.";

// USED BY FILE bakery/payment_methods/payment-network/processor.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'DIRECTebanking.com';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'Pay online with DIRECTebanking.com using your ebanking account: easy, safe, free... No need to sign up or create a new account.';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'Pay your order online using your ebanking account. Just enter your bank account number, clearing number, PIN und TAN.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Learn more about paying safely on the DIRECTebanking.com security page';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'The payment processing is handled by the secure DIRECTebanking.com server.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'After completion of the transaction an order confirmation will be emailed to you.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'DIRECTebanking.com Website';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'I will pay with DIRECTebanking.com';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'To handle the payment processing you will be redirected to a secure DIRECTebanking.com server.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Go to DIRECTebanking.com now';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Total incl tax + shipping';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Thank you for your online payment with DIRECTebanking.com! Your transaction has been completed.<br />Our order confirmation has been emailed to you.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'We will ship your order as soon as possible.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'A problem has occurred. The transaction has not been completed.<br />Please contact the shop admin.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'You have canceled your DIRECTebanking.com payment.<br />Do you like to continue shopping?';

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
	Payment method: DIRECTebanking.com

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