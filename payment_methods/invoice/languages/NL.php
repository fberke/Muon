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
$MOD_BAKERY[$payment_method]['TXT_BANK_ACCOUNT'] = 'Winkel Bankrekening';
$MOD_BAKERY[$payment_method]['TXT_INVOICE_TEMPLATE'] = 'Factuur Template';
$MOD_BAKERY[$payment_method]['TXT_INVOICE_ALERT'] = '1. Reminder Alert after';
$MOD_BAKERY[$payment_method]['TXT_REMINDER_ALERT'] = '2. Reminder Alert after';

// USED BY FILE bakery/payment_methods/invoice/processor.php
$MOD_BAKERY[$payment_method]['TXT_INVOICE_PAYMENT'] = 'Factuur';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'U wordt verzocht het factuurbedrag over te maken op onze bankrekening.';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Ik betaal na ontvangst van de factuur';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'U ontvangt per email een bevestiging van uw bestelling.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Uw bestelling wordt zo spoedig mogelijk verzonden.';

// INVOICE TEMPLATE
$MOD_BAKERY[$payment_method]['INVOICE_TEMPLATE'] = '<img src="[WB_URL]/modules/bakery/images/logo.gif" width="690" height="75" alt="[SHOP_NAME] Logo" class="mod_bakery_logo_b" />
<br />
<p class="mod_bakery_shop_address_b">[SHOP_NAME] | Bedrijfsnaam | Adres | Postcode/Plaats | Land</p>
<br /><br /><br />
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_INVOICE]">[CUST_ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_DELIVERY_NOTE]">[ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_REMINDER]">[CUST_ADDRESS]</p>
<br /><br /><br /><br /><br /><br />
<h2>[TITLE]</h2>
<p class="mod_bakery_invoice_no_b">Besteldatum: [ORDER_DATE]<br />
Bestelling: [ORDER_ID] | [CURRENT_DATE]</p>
<br />
[ITEM_LIST]
<br /><br /><br />

<div style="display: [DISPLAY_INVOICE]">
<p class="mod_bakery_thank_you_b">Bedankt voor uw bestelling bij [SHOP_NAME].</p>
<p class="mod_bakery_pay_invoice_b">U wordt vriendelijk verzocht het totaalbedrag binnen 30 dagen over te maken op onze bankrekening:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<div style="display: [DISPLAY_DELIVERY_NOTE]">
<p class="mod_bakery_thank_you_b">Bedankt voor uw bestelling bij [SHOP_NAME].</p>
</div>

<div style="display: [DISPLAY_REMINDER]">
<p class="mod_bakery_pay_invoice_b">Als u reeds heeft betaald kunt u dit bericht negeren. Zoniet, dan verzoeken wij u het openstaande bedrag binnen 10 dagen naar onze rekening over te maken:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<br /><br />';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'Bevestiging van uw [SHOP_NAME] bestelling';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Geachte [CUSTOMER_NAME]

Bedankt voor uw bestelling bij [SHOP_NAME].
Hieronder vind u een overzicht van de door u bestelde produkten:
[ITEM_LIST]

De bestelling zal worden verzonden naar:
[ADDRESS]
		
De factuur zal worden verzonden naar:
[CUST_ADDRESS]


Bedankt voor het in ons gestelde vertrouwen.

Met vriendelijke groeten,
[SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nieuwe bestelling bij [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Geachte [SHOP_NAME] Administrator

NIEUWE BESTELLING BIJ [SHOP_NAME]:
	Bestelling #: [ORDER_ID]
	Betaal methode: Factuur

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