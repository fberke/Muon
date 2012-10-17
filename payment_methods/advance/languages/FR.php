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
$MOD_BAKERY[$payment_method]['TXT_ADVANCE_PAYMENT'] = 'Paiement anticip&eacute;';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'Veuillez cr&eacute;diter le montant de la commande sur notre compte bancaire.';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'J&apos;accepte le paiement anticip&eacute;';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Vous allez recevoir un email de confirmation de votre commande contenant les informations de paiement.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Votre commande sera exp&eacute;di&eacute;e une fois le paiement confirm&eacute;.';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Confirmation et facture pour votre commande sur [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Cher [CUSTOMER_NAME]

Merci beaucoup pour votre commande sur [SHOP_NAME].
Veuillez trouver ci-dessous les informations concernant les article command&eacute;s:
[ITEM_LIST]

Veuillez cr&eacute;diter le montant de la commande sur notre compte bancaire.
[BANK_ACCOUNT]

Une fois le paiement confirm&eacute; votre commande sera exp&eacute;di&eacute; &agrave; l&apos;adresse suivante:

[ADDRESS]


Nous vous remercions d&apos;avoir fait vos achats sur notre site.

[SHOP_NAME] vous remercie pour votre commande.

';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nouvelle commande sur [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Cher administrateur de [SHOP_NAME] 

NOUVELLE COMMANDE SUR [SHOP_NAME]:
		   Commande #: [ORDER_ID]
  M&eacute;thode de paiement: Paiement anticip&eacute;

Adresse de Livraison:
[ADDRESS]

Adresse de Facturation:
[CUST_ADDRESS]

Liste des articles command&eacute;s: 
[ITEM_LIST]


Meilleures consid&eacute;rations,
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