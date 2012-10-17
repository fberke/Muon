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
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'iDEAL Online-Zahlung &uuml;ber Ihr eBanking Konto auf vertraute, sichere und einfache Art und Weise.';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'W&auml;hlen Sie Ihre Bank aus.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Mehr Informationen zur Zahlungssicherheit finden Sie auf der';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'Sie werden direkt zur vertrauten und sicheren eBanking-Seite Ihrer Bank weitergeleitet, wo Sie die Zahlung genehmigen.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'Nach Ihrer Transaktion erhalten Sie per E-Mail unsere Auftragsbest&auml;tigung und von Ihrer Bank eine Zahlungsbest&auml;tigung.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'iDEAL Website';
$MOD_BAKERY[$payment_method]['TXT_SELECT_BANK'] = 'Bank ausw&auml;hlen';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ich bezahle per iDEAL';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'Zur Zahlungsabwicklung werden Sie zu einem sicheren Server Ihrer Onlinebank weitergeleitet.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Jetzt zu iDEAL wechseln';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Summe inkl. Mwst &amp; Versand';
$MOD_BAKERY[$payment_method]['ERROR_CREATING_PM'] = 'FEHLERMELDUNG: Die Zahlung konnte nicht erstellt werden.';
$MOD_BAKERY[$payment_method]['ERROR_NO_BANK_SELECTED'] = 'FEHLERMELDUNG: Bitte w&auml;hlen Sie eine Bank aus.';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Besten Danke f&uuml;r Ihre online Zahlung. Ihre Transaktion wurde abgeschlossen.<br />Unsere Auftragsbest&auml;tigung wurde Ihnen per E-Mail zugesandt.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Die gew&uuml;nschten Artikel senden wir Ihnen unverz&uuml;glich zu.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'Es ist ein Probleme aufgetreten. Ihre Transaktion konnte nicht abgeschlossen werden.<br />Bitte wenden Sie sich an den Shop-Betreiber.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'Sie haben Ihre iDEAL Zahlung abgebrochen.<br />M&ouml;chten Sie Ihren Einkauf trotzdem fortsetzen?';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Best�tigung f�r Ihre [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank f�r Ihren Einkauf bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Die gew�nschten Artikel werden wir Ihnen unverz�glich an folgende Adresse senden:

[ADDRESS]


Wir danken f�r das uns entgegengebrachte Vertrauen.

Mit freundlichen Gr�ssen
[SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Neue [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Hallo [SHOP_NAME] Admin

NEUE BESTELLUNG BEI [SHOP_NAME]:
	Bestellnummer: [ORDER_ID]
	Zahlungsart: Mollie (iDEAL)

Lieferadresse:
[ADDRESS]

Folgende Artikel wurden bestellt: 
[ITEM_LIST]


Mit freundlichen Gr�ssen
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