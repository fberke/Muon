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
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'Met iDEAL kunt u vertrouwd, veilig en gemakkelijk uw online aankopen afrekenen. Als internetbankierder kunt u direct gebruik maken van iDEAL.';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'Selecteer de bank waar u uw bankzaken al online regelt.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Lees meer over het veilig betalen op de iDEAL veilig pagina';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'U komt direct in het bekende en beveligde internetbankier pagina van uw bank. Op de voor u bekende manier keurt u de betaling goed.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'Nadat de betaling is gedaan ontvangt u een email met onze orderbevestiging en u ontvangt een bevestiging van uw bank.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'iDEAL Website';
$MOD_BAKERY[$payment_method]['TXT_SELECT_BANK'] = 'Kies uw bank';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ik betaal via iDEAL';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'Om de betaling uit te voeren wordt u nu doorgestuurd naar de beveiligde website van uw bank.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Ga nu naar uw bank';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Totaal bedrag incl. BTW en verzendkosten';
$MOD_BAKERY[$payment_method]['ERROR_CREATING_PM'] = 'Er is en fout opgetreden. Uw betaling kon niet worden geinitialiseerd.';
$MOD_BAKERY[$payment_method]['ERROR_NO_BANK_SELECTED'] = 'Er is en fout opgetreden. Kies eerst uw bank.';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Bedankt voor uw online betaling. Uw transactie is geaccepteerd.<br />Onze orderbevestiging zijn naar per email naar u verzonden.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Uw bestelling wordt zo spoedig mogelijk verzonden.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'Er is een probleem opgetreden. Uw betaling is niet uitgevoerd.<br />Neem contact op met de winkel beheerder.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'U heeft uw iDEAL betaling afgebroken.<br />Wilt u verder gaan met winkelen?';

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
	Betaal methode: Mollie (iDEAL)

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