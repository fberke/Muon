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
$MOD_BAKERY[$payment_method]['TXT_PICKUP_PAYMENT'] = 'Abholung';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'Bezahlen Sie Ihre Bestellung bar bei Abholung.<br /><strong>Die in der Rechnung auf&shy;ge&shy;führten Versand&shy;kosten gelten für Sie selbst&shy;verständ&shy;lich nicht!</strong>';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ich bezahle bar bei Abholung';

// USED BY FILE bakery/view.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Sie erhalten von uns eine E-Mail mit der Auftragsbestätigung und der Abholadresse. Wir werden uns telefonisch bei Ihnen für einen möglichen Abholtermin melden.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Bei Übergabe der Ware können Sie diese prüfen und bar bezahlen.';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bestätigung und Informationen für Ihre [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank für Ihren Einkauf bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Sie können den bestellten Artikel bei der Übergabe überprüfen und bar bezahlen.

Die Abholadresse lautet:
NAME/FIRMA
STRASSE
PLZ ORT

WICHTIG: Wir kontaktieren Sie nach dieser Bestellung telefonisch, um einen Abholtermin zu vereinbaren.

Wir danken für das uns entgegengebrachte Vertrauen.

Mit freundlichen Grüßen
[SHOP_NAME]

';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Neue [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Hallo [SHOP_NAME] Admin

NEUE BESTELLUNG BEI [SHOP_NAME]:
	Bestellnummer: [ORDER_ID]
	Zahlungsart: Abholung

Kundenanschrift:
[ADDRESS]

Folgende Artikel wurden bestellt: 
[ITEM_LIST]


Mit freundlichen Grüssen
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
