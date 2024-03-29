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
$MOD_BAKERY[$payment_method]['TXT_USER_ID'] = 'Klant nummer';
$MOD_BAKERY[$payment_method]['TXT_PROJECT_ID'] = 'Project nummer';
$MOD_BAKERY[$payment_method]['TXT_PROJECT_PW'] = 'Project wachtwoord';
$MOD_BAKERY[$payment_method]['TXT_NOTIFICATION_PW'] = 'Berichten wachtwoord';
$MOD_BAKERY[$payment_method]['TXT_NOTICE'] = "
<b>DIRECTebanking.com Uitgebreide instellingen</b><br />
Login in uw <a href='https://www.sofortueberweisung.de/payment/users/login' target='_blank'>DIRECTebanking.com</a> account: Ga naar &quot;Mijn projecten&quot; &gt; &quot;Selecteer een project&quot; &gt; &quot;Uitgebreide instellingen &quot;<br /><br />

<b>Shopsysteem interface:</b> Activeer &quot;Automatisch doorlinken&quot; en copy&amp;paste de volledge url hieronder in het veld &quot;Succes link&quot;:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&transaction_id=-TRANSACTION-' readonly='true' onclick='this.select();' style='width: 98%;' />

Copy&amp;paste de volledige url hieronder in het veld &quot;Afbreken link&quot;:<input type='text' value='".$url['scheme']."://-USER_VARIABLE_1-?pm=payment-network&amp;status=canceled' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Berichten:</b> Voeg email berichten <u>en</u> HTTP berichten to via de <i>POST</i>-methode en voeg de onderstaande url toe:<input type='text' value='".WB_URL."/modules/bakery/payment_methods/payment-network/report.php' readonly='true' onclick='this.select();' style='width: 98%;' /><br /><br />

<b>Project wachtwoord, berichten wachtwoord en input controle:</b> Maak een project wachtwoord en een berichten wachtwoord aan <u>en</u> activeer de input controle door middel van het hash algorithme <i>SHA1</i>.";

// USED BY FILE bakery/payment_methods/payment-network/processor.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'DIRECTebanking.com';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_1'] = 'Betaal online via DIRECTebanking.com met uw ebanking account: makkelijk, veilig, gratis... Aanmelden voor een account is niet nodig.';
$MOD_BAKERY[$payment_method]['TXT_PAY_ONLINE_2'] = 'Betaal uw bestelling online met uw ebanking account. Geef uw bankrekening, clearing number, PIN en TAN.';
$MOD_BAKERY[$payment_method]['TXT_SECURITY'] = 'Lees meer over het veilig betalen op de DIRECTebanking.com veilig pagina';
$MOD_BAKERY[$payment_method]['TXT_SECURE'] = 'De betaling wordt uitgevoerd op de beveligde DIRECTebanking.com server.';
$MOD_BAKERY[$payment_method]['TXT_CONFIRMATION_NOTICE'] = 'Nadat de betaling is gedaan ontvangt u een email met onze orderbevestiging van uw betaling.';
$MOD_BAKERY[$payment_method]['TXT_WEBSITE'] = 'DIRECTebanking.com Website';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ik betaal via DIRECTebanking.com';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT'] = 'Om de betaling uit te voeren wordt u doorgestuurd naar een beveiligde DIRECTebanking.com server.';
$MOD_BAKERY[$payment_method]['TXT_REDIRECT_NOW'] = 'Ga nu naar DIRECTebanking.com';
$MOD_BAKERY[$payment_method]['TXT_AGGREGATED_ITEMS'] = 'Totaal incl BTW + verzendkosten';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Bedankt voor uw online betaling via DIRECTebanking.com. Uw transactie is geaccepteerd.<br />Onze orderbevestiging van uw betaling zijn naar per email naar u verzonden.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Uw bestelling wordt zo spoedig mogelijk verzonden.';
$MOD_BAKERY[$payment_method]['ERROR'] = 'Er is een probleem opgetreden. Uw betaling is niet uitgevoerd.<br />Neem contact op met de winkel beheerder.';
$MOD_BAKERY[$payment_method]['CANCELED'] = 'U heeft uw DIRECTebanking.com betaling afgebroken.<br />Wilt u verder gaan met winkelen?';

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
	Betaal methode: DIRECTebanking.com

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