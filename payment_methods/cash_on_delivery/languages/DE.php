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
$MOD_BAKERY[$payment_method]['TXT_COD_PAYMENT'] = 'Nachnahme';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ich bezahle per Nachnahme';

// USED BY FILE bakery/view.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Sie erhalten von uns eine E-Mail mit der Auftragsbest&auml;tigung und den Zahlungsinformationen.<br />Auf Wunsch können Sie mit uns einen speziellen Versandtermin vereinbaren.';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'Sie bezahlen den vollständigen Rechnungsbetrag zzgl. Gebühren bei Lieferung an Ihren Paketzusteller.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Beachten Sie bitte folgendes:<br /><strong>Halten Sie Ihr Geld möglichst passend bereit!</strong><br /><strong>Eine Nachnahmegebühr zusätzlich zum Versand erhebt der Paketdienst und ist direkt an diesen zu entrichten</strong>.';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bestätigung und Informationen über Ihre [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank für Ihren Einkauf bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Ihre Bestellung wird an folgende Lieferadresse versendet:
[ADDRESS]

Sie haben ausgewählt, Ihre Bestellung per Nachnahme zu bezahlen.
Halten Sie am Tag der Lieferung Ihr Geld möglichst passend bereit! Eine Nachnahmegebühr zusätzlich zum Versand erhebt der Paketdienst und ist direkt an diesen zu entrichten.

WICHTIG: Gern können Sie uns zwecks eines speziellen Versandtermines ansprechen.

Wir danken für das uns entgegengebrachte Vertrauen.

Mit freundlichen Grüßen
[SHOP_NAME]

';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Neue [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Hallo [SHOP_NAME] Admin

NEUE BESTELLUNG BEI [SHOP_NAME]:
	Bestellnummer: [ORDER_ID]
	Zahlungsart: Nachnahme

Lieferadresse:
[ADDRESS]

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