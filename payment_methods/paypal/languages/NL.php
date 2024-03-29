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
$MOD_BAKERY[$payment_method]['TXT_AUTH_TOKEN'] = 'DBB Identiteitscode';

$MOD_BAKERY[$payment_method]['TXT_NOTICE'] = '
<b>Voorkeuren websitebetalingen</b><br />
Log in in uw PayPal account: Ga naar &quot;Mijn rekening&quot; &gt; &quot;Profiel&quot; &gt; &quot;Voorkeuren websitebetalingen&quot;<br />

<b>De koper wordt automatisch teruggeleid naar webwinkel:</b> Klik de &quot;Automatisch teruggeleid&quot; radio button <i>Aan</i>.<br />
<b>Retour URL:</b> Voer het adres in zoals getoond in het veld &quot;Retour URL&quot;:<input type="text" value="' . WB_URL . '" readonly="true" onclick="this.select();" style="width: 98%;" />

<b>Overdracht van betaalgegevens:</b> Klik de &quot;Overdracht van betaalgegevens&quot; radio button <i>Aan</i> en klik vervolgens op <i>Opslaan</i>.<br />
Een bevestiging wordt getoond om aan te geven dat u &quot;Overdracht van betaalgegevens&quot; succesvol heeft ingeschakeld. Uw identiteitscode staat in dat bericht, en ook onder de Overdracht van betaalgegevens Aan/Uit radio buttons. Kopieer&amp;plak uw identiteitscode in het tekstveld hierboven.<br /><br />

<b>Direct betaalbericht (DBB)</b><br />
Ga naar &quot;Mijn rekening&quot; &gt; &quot;Profiel&quot; &gt; &quot;Voorkeuren Direct betaalbericht&quot;.<br />
Als u klikt op &quot;Instellingen voor DBB&quot; bewerken, wordt u doorgestuurd naar de configuratiepagina.<br />
Kopieer&amp;plak het volledige adres zoals getoond in het veld &quot;Berichtgevings-URL&quot;:<input type="text" value="' . WB_URL . '/modules/bakery/payment_methods/paypal/ipn.php" readonly="true" onclick="this.select();" style="width: 98%;" />
Klik op de  &quot;DBB-berichten ontvangen (ingeschakeld)&quot; radio button en sla de wijzigingen op.<br />';

// USED BY FILE bakery/payment_methods/paypal/processor.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'Creditcard (PayPal)';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'Betaal online via PayPal met uw creditcard: makkelijk, veilig, gratis...';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'Betaal uw bestelling online met uw credit card of uw PayPal rekening.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Lees meer over het veilig betalen op de PayPal Veilig Winkelen pagina';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'De betaling wordt uitgevoerd op de beveligde PayPal server.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'Nadat de betaling is gedaan ontvangt u een email met onze orderbevestiging en een Paypal bevestiging van uw betaling.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'PayPal Website';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ik betaal via PayPal';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'Om de betaling uit te voeren wordt u doorgestuurd naar een beveiligde PayPal server.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Ga nu naar PayPal';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Totaal bedrag incl. BTW en verzendkosten';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Bedankt voor uw online betaling. Uw transactie is geaccepteerd.<br />Onze orderbevestiging en een PayPal bevestiging van uw betaling zijn naar per email naar u verzonden.';
$MOD_BAKERY[$payment_method]['TXT_PENDING'] = 'Bedankt voor uw online betaling! Uw transactie wordt zo spoedig mogelijk afgehandeld.<br />Een bevestiging van uw bestelling en een PayPal betalings bevestiging worden per email aan u verzonden.';
$MOD_BAKERY[$payment_method]['TXT_TRANSACTION_STATUS'] = "LET OP:\n\tDe transactie status is \"PENDING\".\n\tOm de details te bekijken kunt u inloggen in uw PayPal rekening.";
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Uw bestelling wordt zo spoedig mogelijk verzonden.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'Er is een probleem opgetreden. Uw betaling is niet uitgevoerd.<br />Neem contact op met de winkel beheerder.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'U heeft uw PayPal betaling afgebroken.<br />Wilt u verder gaan met winkelen?';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bevestiging van uw [SHOP_NAME] bestelling';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Geachte [CUSTOMER_NAME]

Bedankt voor uw bestelling bij [SHOP_NAME].
Hieronder vind u een overzicht van de door u bestelde produkten:
[ITEM_LIST]

Wij zullen de goederen verzenden naar:

[ADDRESS]


Bedankt voor het in ons gestelde vertrouwen.

Met vriendelijke groeten,
[SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nieuwe bestelling bij [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Geachte [SHOP_NAME] Administrator

NIEUWE BESTELLING BIJ [SHOP_NAME]:
	Bestelling #: [ORDER_ID]
	Betaal methode: PayPal
[TRANSACTION_STATUS]

Aflever adres:
[ADDRESS]

Factuur adres:
[CUST_ADDRESS]

Bestellijst: 
[ITEM_LIST]


Met vriendelijke groet,
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