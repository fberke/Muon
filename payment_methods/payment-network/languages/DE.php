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
$MOD_BAKERY[$payment_method]['TXT_USER_ID'] = 'Kundennummer';
$MOD_BAKERY[$payment_method]['TXT_PROJECT_ID'] = 'Projektnummer';
$MOD_BAKERY[$payment_method]['TXT_PROJECT_PW'] = 'Projekt Passwort';
$MOD_BAKERY[$payment_method]['TXT_NOTIFICATION_PW'] = 'Benachrichtigungspasswort';
$MOD_BAKERY[$payment_method]['TXT_NOTICE'] = "
<b>sofort&uuml;berweisung.de erweiterte Einstellungen</b><br />
Loggen Sie sich in Ihr <a href='https://www.sofortueberweisung.de/payment/users/login' target='_blank'>sofortueberweisung.de</a> Konto ein: Gehen Sie zu &quot;Meine Projekte&quot; &gt; &quot;Projekt ausw&auml;hlen&quot; &gt; &quot;Erweiterte Einstellungen&quot;<br /><br />

<b>Shopsystem-Schnittstelle:</b> Aktivieren Sie &quot;Automatische Weiterleitung&quot; und geben Sie unter &quot;Erfolgslink&quot; folgende vollst&auml;ndige URL ein:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&transaction_id=-TRANSACTION-' readonly='true' onclick='this.select();' style='width: 98%;' />

Geben Sie unter &quot;Abbruchlink&quot; die folgende vollst&auml;ndige URL ein:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&amp;status=canceled' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Benachrichtigungen:</b> Erstellen Sie eine E-Mail Benachrichtigung <u>und</u> eine HTTP Benachrichtigung mit der <i>POST</i>-Methode an die folgende vollst&auml;ndige URL:<input type='text' value='".WB_URL."/modules/bakery/payment_methods/payment-network/report.php' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Projekt-Passwort, Benachrichtigungspasswort und Input-Pr&uuml;fung:</b> Legen Sie ein Projekt-Passwort und ein Benachrichtigungspasswort fest <u>und</u> aktivieren Sie die Input-Pr&uuml;fung mit dem Hash-Algorithmus <i>SHA1</i>.";

// USED BY FILE bakery/payment_methods/payment-network/processor.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'sofort&uuml;berweisung.de';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'Mit sofort&uuml;berweisung.de k&ouml;nnen Sie bequem, einfach und sicher ohne Registrierung mit Ihrem Online-Banking Konto bezahlen.';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'Bezahlen Sie Ihre Bestellung online &uuml;ber Ihr Online-Banking Konto. Sie ben&ouml;tigen lediglich Bankkontonummer, Bankleitzahl, PIN und TAN.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Mehr Informationen zur Zahlungssicherheit finden Sie auf der';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'Die Zahlungsabwicklung l&auml;uft &uuml;ber einen sicheren Server von sofort&uuml;berweisung.de.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'Nach Ihrer Transaktion erhalten Sie per E-Mail unsere Auftragsbest&auml;tigung.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'sofort&uuml;berweisung.de Website';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ich bezahle per sofort&uuml;berweisung.de';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'Zur Zahlungsabwicklung werden Sie zu einen sicheren Server von sofort&uuml;berweisung.de weitergeleitet.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Jetzt zu sofort&uuml;berweisung.de wechseln';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Summe inkl Mwst + Versand';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Besten Danke f&uuml;r Ihre online Zahlung. Ihre Transaktion bei sofort&uuml;berweisung.de wurde abgeschlossen.<br />Unsere Auftragsbest&auml;tigung wurde Ihnen per E-Mail zugesandt.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Die gew&uuml;nschten Artikel senden wir Ihnen unverz&uuml;glich zu.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'Es ist ein Probleme aufgetreten. Ihre Transaktion konnte nicht abgeschlossen werden.<br />Bitte wenden Sie sich an den Shop-Betreiber.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'Sie haben Ihre Zahlung bei sofort&uuml;berweisung.de abgebrochen.<br />M&ouml;chten Sie Ihren Einkauf trotzdem fortsetzen?';

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
	Zahlungsart: sofort�berweisung.de

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