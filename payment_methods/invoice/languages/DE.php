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
$MOD_BAKERY[$payment_method]['TXT_BANK_ACCOUNT'] = 'Shop Bankkonto';
$MOD_BAKERY[$payment_method]['TXT_INVOICE_TEMPLATE'] = 'Rechnungstemplate';
$MOD_BAKERY[$payment_method]['TXT_INVOICE_ALERT'] = '1. Zahlungserinnerung nach';
$MOD_BAKERY[$payment_method]['TXT_REMINDER_ALERT'] = '2. Zahlungserinnerung nach';

// USED BY FILE bakery/payment_methods/invoice/processor.php
$MOD_BAKERY[$payment_method]['TXT_INVOICE_PAYMENT'] = 'Rechnung';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'Bitte bezahlen Sie den Rechnungsbetrag innerhalb der in der Rechnung angegebenen Zahlungsfrist.';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ich bezahle per Rechnung';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Sie erhalten von uns eine E-Mail mit der Auftragsbest&auml;tigung.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Die gew&uuml;nschten Artikel senden wir Ihnen unverz&uuml;glich zu.';

// INVOICE TEMPLATE
$MOD_BAKERY[$payment_method]['INVOICE_TEMPLATE'] = '<img src="[WB_URL]/modules/bakery/images/logo.gif" width="690" height="75" alt="[SHOP_NAME] Logo" class="mod_bakery_logo_b" />
<br />
<p class="mod_bakery_shop_address_b">[SHOP_NAME] | Firma | Strasse Nummer | PLZ Stadt | Land</p>
<br /><br /><br />
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_INVOICE]">[CUST_ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_DELIVERY_NOTE]">[ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_REMINDER]">[CUST_ADDRESS]</p>
<br /><br /><br /><br /><br /><br />
<h2>[TITLE]</h2>
<p class="mod_bakery_invoice_no_b">Bestelldatum: [ORDER_DATE]<br />
Rechnungsnummer: [ORDER_ID] | [CURRENT_DATE]</p>
<br />
[ITEM_LIST]
<br /><br /><br />

<div style="display: [DISPLAY_INVOICE]">
<p class="mod_bakery_thank_you_b">Vielen Dank f&uuml;r Ihre Bestellung.</p>
<p class="mod_bakery_pay_invoice_b">Bitte bezahlen Sie die Rechnung innert 30 Tagen auf unser Konto:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<div style="display: [DISPLAY_DELIVERY_NOTE]">
<p class="mod_bakery_thank_you_b">Vielen Dank f&uuml;r Ihre Bestellung.</p>
</div>

<div style="display: [DISPLAY_REMINDER]">
<p class="mod_bakery_pay_invoice_b">Sollten Sie die Rechnung zwischenzeitlich beglichen haben, erachten sie dieses Schreiben als gegenstandslos. Ansonsten bitten wir Sie, den Rechnungsbetrag innert 10 Tagen auf unser Konto zu &uuml;berweisen:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<br /><br />';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bestätigung für Ihre [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Guten Tag [CUSTOMER_NAME]

Herzlichen Dank für Ihren Einkauf bei [SHOP_NAME].
Sie haben die unten stehenden Artikel aus unserem Sortiment bestellt:
[ITEM_LIST]

Die gewünschten Artikel werden wir Ihnen unverzüglich an folgende Adresse senden:

[ADDRESS]

Die Rechnung werden wir Ihnen an folgende Adresse senden:

[CUST_ADDRESS]


Wir danken für das uns entgegengebrachte Vertrauen.

Mit freundlichen Grüßen
[SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Neue [SHOP_NAME] Bestellung';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Hallo [SHOP_NAME] Admin

NEUE BESTELLUNG BEI [SHOP_NAME]:
	Bestellnummer: [ORDER_ID]
	Zahlungsart: Rechnung

Lieferadresse:
[ADDRESS]

Rechnungsadresse:
[CUST_ADDRESS]

Folgende Artikel wurden bestellt: 
[ITEM_LIST]


Mit freundlichen Grüßen
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