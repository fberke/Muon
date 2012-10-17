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
$MOD_BAKERY[$payment_method]['TXT_EMAIL'] = 'PayPal E-Mail';
$MOD_BAKERY[$payment_method]['TXT_PAGE'] = 'PayPal Seite';
$MOD_BAKERY[$payment_method]['TXT_AUTH_TOKEN'] = 'Identit&auml;tstoken';

$MOD_BAKERY[$payment_method]['TXT_NOTICE'] = '
<b>Website-Zahlungsoptionen</b><br />
Loggen Sie sich in Ihr PayPal Konto ein: Gehen Sie zu &quot;MeinKonto&quot; &gt; &quot;Mein Profil&quot; &gt; &quot;Website-Zahlungsoptionen&quot;.<br />

<b>Automatische R&uuml;ckleitung:</b> Aktivieren Sie &quot;Automatische R&uuml;ckleitung&quot;.<br />
<b>R&uuml;ckleitungs-URL:</b> Geben Sie folgende URL als &quot;R&uuml;ckleitungs-URL&quot; an:<input type="text" value="' . WB_URL . '" readonly="true" onclick="this.select();" style="width: 98%;" />

<b>&Uuml;bertragung der Zahlungsdaten:</b> Aktivieren Sie &quot;&Uuml;bertragung der Zahlungsdaten&quot; und speichern Ihre Einstellung.<br />
Eine Meldung best&auml;tigt Ihnen die erfolgreiche Aktivierung. Innerhalb dieser Meldung wird Ihnen Ihr Identit&auml;tstoken angezeigt, welches Sie ins Feld direkt oberhalb dieser Anweisung kopieren m&uuml;ssen. Ihr Identit&auml;tstoken wird auch unterhalb der &quot;&Uuml;bertragung der Zahlungsdaten&quot; Radio-Buttons angezeigt.<br /><br />

<b>Sofortige Zahlungsbest&auml;tigung (IPN)</b><br />
Gehen Sie zu &quot;MeinKonto&quot; &gt; &quot;Mein Profil&quot; &gt; &quot;Einstellungen f&uuml;r sofortige Zahlungsbest&auml;tigung&quot;.<br />
Durch Klicken auf &quot;Einstellungen f&uuml;r sofortige Zahlungsbest&auml;tigungen bearbeiten&quot; gelangen Sie auf die Konfigurationsseite.<br />
Kopieren Sie die unten stehende URL und f&uuml;gen Sie sie vollst&auml;ndig ins Feld &quot;Benachrichtigungs-URL&quot; auf der Konfigurationsseite ein:<input type="text" value="' . WB_URL . '/modules/bakery/payment_methods/paypal/ipn.php" readonly="true" onclick="this.select();" style="width: 98%;" />
Aktivieren Sie &quot;Sofortige Zahlungsbest&auml;tigungen erhalten (aktiviert)&quot; und speichern Ihre Einstellung.<br />';

// USED BY FILE bakery/payment_methods/paypal/processor.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'Kreditkarte (PayPal)';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'Bezahlen Sie online mit allen g&auml;ngigen Kreditkarten per PayPal: schnell, sicher, problemlos...';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'Bezahlen Sie Ihre Bestellung online mit allen g&auml;ngigen Kreditkarten per PayPal oder auch per PayPal-Zahlung.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Mehr Informationen zur Zahlungssicherheit finden Sie auf der';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'Die Zahlungsabwicklung l&auml;uft &uuml;ber einen sicheren PayPal Server.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'Nach Ihrer Transaktion erhalten Sie per E-Mail unsere Auftragsbest&auml;tigung sowie eine Zahlungsbest&auml;tigung von PayPal.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'PayPal Website';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ich bezahle per PayPal';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'Zur Zahlungsabwicklung werden Sie zu einem sicheren PayPal Server weitergeleitet.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Jetzt zu PayPal wechseln';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Gesamtsumme inkl. Mwst und Versand';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Besten Danke f&uuml;r Ihre online Zahlung. Ihre Transaktion wurde abgeschlossen.<br />Unsere Auftragsbest&auml;tigung und eine Zahlungsbest&auml;tigung von PayPal wurden Ihnen per E-Mail zugesandt.';
$MOD_BAKERY[$payment_method]['TXT_PENDING'] = 'Besten Danke f&uuml;r Ihre online Zahlung. Ihre Transaktion wird in K&uuml;rze bearbeitet.<br />Unsere Auftragsbest&auml;tigung und eine Zahlungsbest&auml;tigung von PayPal wird Ihnen per E-Mail zugesandt.';
$MOD_BAKERY[$payment_method]['TXT_TRANSACTION_STATUS'] = "ACHTUNG:\n\tDie Transaktion ist noch \"OFFEN\".\n\tAlle Details zu dieser Zahlung finden Sie in Ihrer PayPal-Konto�bersicht.";
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Die gew&uuml;nschten Artikel senden wir Ihnen unverz&uuml;glich zu.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'Es ist ein Probleme aufgetreten. Ihre Transaktion konnte nicht abgeschlossen werden.<br />Bitte wenden Sie sich an den Shop-Betreiber.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'Sie haben Ihre Zahlung bei PayPal abgebrochen.<br />M&ouml;chten Sie Ihren Einkauf trotzdem fortsetzen?';

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
	Zahlungsart: PayPal
[TRANSACTION_STATUS]

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