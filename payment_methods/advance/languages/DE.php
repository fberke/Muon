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
 

// PAYMENT METHOD ADVANCE PAYMENT
// ******************************

// SETTINGS - USED BY BACKEND


// USED BY FILE bakery/payment_methods/advance/processor.php
$MOD_BAKERY[$payment_method]['TXT_ADVANCE_PAYMENT'] = 'Vorauszahlung';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'Bezahlen Sie Ihre Bestellung im Voraus auf unser Konto.';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ich bezahle per Vorauszahlung';

// USED BY FILE bakery/view.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Sie erhalten von uns eine E-Mail mit der Auftragsbestätigung und den Zahlungsinformationen.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Sobald Ihre Zahlung bei uns eingegangen ist, werden wir Ihnen die gewünschten Artikel unverz&uuml;glich zusenden.';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bestätigung und Informationen über Ihre [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank für Ihren Einkauf bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Bitte bezahlen Sie die Gesamtsumme im Voraus auf unser Konto:
[BANK_ACCOUNT]

Sobald Ihre Zahlung bei uns eingegangen ist, werden wir Ihnen die gewünschten Artikel unverzüglich an folgende Adresse senden:

[ADDRESS]


Wir danken für das uns entgegengebrachte Vertrauen.

Mit freundlichen Grüßen
[SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Neue [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Hallo [SHOP_NAME] Admin

NEUE BESTELLUNG BEI [SHOP_NAME]:
	Bestellnummer: [ORDER_ID]
	Zahlungsart: Vorauszahlung

Lieferanschrift:
[ADDRESS]

Rechnungsanschrift:
[CUST_ADDRESS]

Folgende Artikel wurden bestellt: 
[ITEM_LIST]


E-Mail wurde automatisch generiert von
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