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
 

/*
  ***********************
  TRANSLATORS PLEASE NOTE
  ***********************
  
  Thank you for translating Bakery!
  Include your credits in the header of this file right above the licence terms.
  Please post your localisation file on the WB forum at http://forum.websitebaker2.org
  
  This file is saved using charset UTF-8.
  
  For other languages you might use more applicable charsets.
  Convert this localisation file to the charset encoding that goes best with your language
  and set it as WB default charset in the advanced options of the general WB settings.

*/

// MODULE BAKERY ADDITIONAL CHARS
// Define additional chars to be accepted by the customer address form.
// The chars should be corresponding to the localisation language.
$MOD_BAKERY['ADD_REGEXP_CHARS'] = '';

// Webanalytics page filenames and titles
$MOD_BAKERY_FILENAME['shopping_cart'] = 'view_cart.php';
$MOD_BAKERY_FILENAME['address_form'] = 'view_form.php';
$MOD_BAKERY_FILENAME['order_summary'] = 'view_summary.php';
$MOD_BAKERY_FILENAME['payment_methods'] = 'view_pay.php';
$MOD_BAKERY_FILENAME['payment_error'] = 'view_confirmation_error.php';
$MOD_BAKERY_FILENAME['payment_cancelled'] = 'view_confirmation_cancel.php';
$MOD_BAKERY_FILENAME['payment_success'] = 'view_confirmation_success.php';

$_shop_name = isset($general_settings['shop_name']) ? $general_settings['shop_name'].' - ' : '';
$MOD_BAKERY_TITLE['shopping_cart'] = $_shop_name.'Cart';
$MOD_BAKERY_TITLE['address_form'] = $_shop_name.'Address Form';
$MOD_BAKERY_TITLE['order_summary'] = $_shop_name.'Summary';
$MOD_BAKERY_TITLE['payment_methods'] = $_shop_name.'Payment Methods';
$MOD_BAKERY_TITLE['payment_error'] = $_shop_name.'Payment Error';
$MOD_BAKERY_TITLE['payment_cancelled'] = $_shop_name.'Payment cancelled';
$MOD_BAKERY_TITLE['payment_success'] = $_shop_name.'Payment successful';

// MODULE DESCRIPTION
$module_description = 'Bakery is een WebsiteBaker winkel module met catalogus, winkelwagen, voorraad beheer, bestellingen beheer en een factuur optie. Betaalopties zijn: Vooruitbetaling, Betaling na factuur en/of payment gateways. Meer informatie is te vinden op de <a href="http://www.bakery-shop.ch" target="_blank">Bakery Website</a>.';

// MODULE BAKERY VARIOUS TEXT
$MOD_BAKERY['TXT_SETTINGS'] = 'Instellingen';
$MOD_BAKERY['TXT_GENERAL_SETTINGS'] = 'Algemene instellingen';
$MOD_BAKERY['TXT_PAGE_SETTINGS'] = 'Pagina instellingen';
$MOD_BAKERY['TXT_PAYMENT_METHODS'] = 'Betaalmethodes';
$MOD_BAKERY['TXT_SHOP'] = 'Winkel';
$MOD_BAKERY['TXT_PAYMENT'] = 'Betaling';
$MOD_BAKERY['TXT_EMAIL'] = 'E-Mail';
$MOD_BAKERY['TXT_LAYOUT'] = 'Layout';
$MOD_BAKERY['TXT_PAGE_OFFLINE'] = 'Zet de pagina offline';
$MOD_BAKERY['TXT_OFFLINE_TEXT'] = 'Offline Tekst';
$MOD_BAKERY['TXT_CONTINUE_URL'] = 'Doorgaan met winkelen URL';
$MOD_BAKERY['TXT_SHOP_NAME'] = 'Winkel Name';
$MOD_BAKERY['TXT_OVERVIEW'] = 'Overzicht';
$MOD_BAKERY['TXT_DETAIL'] = 'Detailpagina';
$MOD_BAKERY['TXT_TAC_URL'] = 'Leveringsvoorwaarden URL';
$MOD_BAKERY['TXT_CANCELLATION_URL'] = 'Cancellation URL';
$MOD_BAKERY['TXT_PRIVACY_URL'] = 'Privacy URL';
$MOD_BAKERY['TXT_SHOP_EMAIL'] = 'Winkel E-Mail';
$MOD_BAKERY['TXT_SHOP_COUNTRY'] = 'Winkel Land';
$MOD_BAKERY['TXT_SHOP_STATE'] = 'Winkel State';
$MOD_BAKERY['TXT_ADDRESS_FORM'] = 'Adres Formulier';
$MOD_BAKERY['TXT_SHIPPING_FORM_REQUEST'] = 'indien nodig';
$MOD_BAKERY['TXT_SHIPPING_FORM_HIDEABLE'] = 'verbergbaar';
$MOD_BAKERY['TXT_SHIPPING_FORM_ALWAYS'] = 'altijd';
$MOD_BAKERY['TXT_SHOW_STATE_FIELD'] = 'Laat &quot;State Field&quot; zien';
$MOD_BAKERY['TXT_SHOW_ZIP_END_OF_ADDRESS'] = 'Postcode rechts van het adres';
$MOD_BAKERY['TXT_ALLOW_OUT_OF_STOCK_ORDERS'] = 'Verkopen zonder voorraad toestaan';
$MOD_BAKERY['TXT_SKIP_CART_AFTER_ADDING_ITEM'] = 'Toon geen winkelwagen na het toevoegen van een artikel';
$MOD_BAKERY['TXT_MINICART_STRONGLY_RECOMMENDED'] = 'MiniCart dringend aangeraden';
$MOD_BAKERY['TXT_DISPLAY_SETTINGS_TO_ADMIN_ONLY'] = 'Instellingen alleen aan Admin (id = 1) tonen';
$MOD_BAKERY['TXT_FREE_DEFINABLE_FIELD'] = 'Vrij definieerbaar veld';
$MOD_BAKERY['TXT_STOCK_MODE_TEXT'] = 'Toon voorraad als tekst aan de klant';
$MOD_BAKERY['TXT_STOCK_MODE_IMAGE'] = 'Toon voorraad als afbeelding aan de klant';
$MOD_BAKERY['TXT_STOCK_MODE_NUMBER'] = 'Toon de voorraad als getal aan de klant';
$MOD_BAKERY['TXT_STOCK_MODE_NONE'] = 'Toon de voorraad niet aan de klant';
$MOD_BAKERY['TXT_SHOP_CURRENCY'] = 'Winkel Valuta Code';
$MOD_BAKERY['TXT_SEPARATOR_FOR'] = 'Scheidingsteken voor';
$MOD_BAKERY['TXT_DECIMAL'] = 'Decimalen';
$MOD_BAKERY['TXT_GROUP_OF_THOUSANDS'] = 'Duizenden';

$MOD_BAKERY['TXT_PAYMENT_METHOD'] = 'Betaal Methode';
$MOD_BAKERY['TXT_SELECT_PAYMENT_METHODS'] = 'Kies betaalmethodes';
$MOD_BAKERY['TXT_PAYMENT_METHOD_COD'] = 'COD';
$MOD_BAKERY['TXT_PAYMENT_METHOD_ADVANCE'] = 'Vooruitbetaling';
$MOD_BAKERY['TXT_PAYMENT_METHOD_INVOICE'] = 'Factuur';
$MOD_BAKERY['TXT_PAYMENT_METHOD_PAYMENT_NETWORK'] = 'DIRECTebanking.com';
$MOD_BAKERY['TXT_SKIP_CHECKOUT'] = 'Sla betaalmethode keuze over als maar 1 betaalmethode beschikbaar is';
$MOD_BAKERY['TXT_NO_PAYMENT_METHOD_SETTING'] = 'Een instellingen nodig voor deze betaalmethode.';
$MOD_BAKERY['TXT_NOTICE'] = 'Opmerking';
$MOD_BAKERY['TXT_DAYS'] = 'Dagen';

$MOD_BAKERY['TXT_TAX_RATE'] = 'BTW percentage';
$MOD_BAKERY['TXT_SAVED_TAX_RATE'] = 'Huidig BTW percentage';
$MOD_BAKERY['TXT_SET_TAX_RATE'] = 'BTW percentage instellen';
$MOD_BAKERY['TXT_TAX_INCLUDED'] = 'Bedragen incl. BTW';
$MOD_BAKERY['TXT_TAX_EXCLUDED'] = 'Bedragen excl. BTW';
$MOD_BAKERY['TXT_TAX_FREE'] = 'BTW vrij';
$MOD_BAKERY['TXT_PLUS_SHIPPING'] = 'plus shipping';
$MOD_BAKERY['TXT_DOMESTIC'] = 'binnenland';
$MOD_BAKERY['TXT_ZONE_COUNTRIES'] = 'afwijkende landen (meer keuzes mogelijk)';
$MOD_BAKERY['TXT_ABROAD'] = 'buitenland';
$MOD_BAKERY['TXT_PER_ITEM'] = 'per produkt';
$MOD_BAKERY['TXT_SHIPPING_BASED_ON'] = 'Verzendkosten gebaseerd op';
$MOD_BAKERY['TXT_SHIPPING_METHOD_FLAT'] = 'Een vast bedrag';
$MOD_BAKERY['TXT_SHIPPING_METHOD_ITEMS'] = 'Aantal produkten';
$MOD_BAKERY['TXT_SHIPPING_METHOD_POSITIONS'] = 'Aantal regels';
$MOD_BAKERY['TXT_SHIPPING_METHOD_PERCENTAGE'] = 'Percentage van het subtotaal';
$MOD_BAKERY['TXT_SHIPPING_METHOD_HIGHEST'] = 'Artikel met de hoogste verzendkosten';
$MOD_BAKERY['TXT_SHIPPING_METHOD_NONE'] = 'geen';
$MOD_BAKERY['TXT_FREE_SHIPPING'] = 'Geen verzendkosten';
$MOD_BAKERY['TXT_OVER'] = 'bij bedragen boven de';
$MOD_BAKERY['TXT_SHOW_FREE_SHIPPING_MSG'] = 'Vertel de klant over de verzendkosten limieten';
$MOD_BAKERY['TXT_EMAIL_SUBJECT'] = 'E-Mail Onderwerp';
$MOD_BAKERY['TXT_EMAIL_BODY'] = 'E-Mail Tekst';
$MOD_BAKERY['TXT_ITEM'] = 'Produkt';
$MOD_BAKERY['TXT_ITEMS'] = 'Produkten';
$MOD_BAKERY['TXT_ITEMS_PER_PAGE'] = 'Produkten per Pagina';
$MOD_BAKERY['TXT_NUMBER_OF_COLUMS'] = 'Aantal kolommen';
$MOD_BAKERY['TXT_USE_CAPTCHA'] = 'Gebruik Captcha (nog niet aktief)';
$MOD_BAKERY['TXT_MODIFY_THIS'] = 'Update de pagina instellingen alleen van <b>huidige</b> winkel pagina.';
$MOD_BAKERY['TXT_MODIFY_ALL'] = 'Update de pagina instellingen (behalve de &quot;Doorgaan met winkelen URL&quot;) van <b>alle</b> winkel pagina\'s.';
$MOD_BAKERY['TXT_MODIFY_MULTIPLE'] = 'Update de pagina instellingen (behalve de &quot;Doorgaan met winkelen URL&quot;) alleen van <b>geselecteerd</b> winkel pagina\'s (meer keuzes mogelijk):';

$MOD_BAKERY['PREVIOUS_ITEM'] = '<< Previous Item';
$MOD_BAKERY['ITEM_OVERVIEW'] = 'Item Overview';
$MOD_BAKERY['NEXT_ITEM'] = 'Next Item >>';

$MOD_BAKERY['TXT_ADD_ITEM'] = 'Produkt toevoegen';
$MOD_BAKERY['TXT_NAME'] = 'Omschrijving';
$MOD_BAKERY['TXT_SKU'] = 'Artikelcode';
$MOD_BAKERY['TXT_PRICE'] = 'Prijs';
$MOD_BAKERY['TXT_OPTION_NAME'] = 'Optie naam';
$MOD_BAKERY['TXT_OPTION_ATTRIBUTES'] = 'Opties';
$MOD_BAKERY['TXT_OPTION_PRICE'] = 'Optie prijs';
$MOD_BAKERY['TXT_ITEM_OPTIONS'] = 'Artikel opties';
$MOD_BAKERY['TXT_EG_OPTION_NAME'] = 'bv. kleur';
$MOD_BAKERY['TXT_EG_OPTION_ATTRIBUTE'] = 'bv. Rood';
$MOD_BAKERY['TXT_INCL'] = 'Inclusief';
$MOD_BAKERY['TXT_EXCL_SHIPPING_TAX'] = 'exclusief verzendkosten en BTW';
$MOD_BAKERY['TXT_TAX'] = 'BTW';
$MOD_BAKERY['TXT_QUANTITY'] = 'Aantal';
$MOD_BAKERY['TXT_SUM'] = 'Totaal';
$MOD_BAKERY['TXT_SUBTOTAL'] = 'Subtotaal';
$MOD_BAKERY['TXT_TOTAL'] = 'Totaal';
$MOD_BAKERY['TXT_SHIPPING'] = 'Verzendkosten';
$MOD_BAKERY['TXT_SHIPPING_COST'] = 'Verzendkosten';
$MOD_BAKERY['TXT_DESCRIPTION'] = 'Omschrijving';
$MOD_BAKERY['TXT_CHARACTERISTICS'] = 'Main Product Characteristics';
$MOD_BAKERY['TXT_FULL_DESC'] = 'Omschrijving';
$MOD_BAKERY['TXT_VIEW_ITEM_TEXTAREA'] = 'Notification';
$MOD_BAKERY['TXT_PREVIEW'] = 'Voorbeeld';
$MOD_BAKERY['TXT_FILE_NAME'] = 'Bestandsnaam';
$MOD_BAKERY['TXT_MAIN_IMAGE'] = 'Hoofd afbeelding';
$MOD_BAKERY['TXT_THUMBNAIL'] = 'Thumbnail';
$MOD_BAKERY['TXT_IMAGE'] = 'Afbeelding';
$MOD_BAKERY['TXT_IMAGES'] = 'Afbeeldingen';
$MOD_BAKERY['TXT_MAX_WIDTH'] = 'max. breedte (px)';
$MOD_BAKERY['TXT_MAX_HEIGHT'] = 'max. hoogte (px)';
$MOD_BAKERY['TXT_JPG_QUALITY'] = 'JPG kwaliteit';
$MOD_BAKERY['TXT_NON'] = 'geen';
$MOD_BAKERY['TXT_ITEM_TO_PAGE'] = 'Verplaats item naar pagina';
$MOD_BAKERY['TXT_MOVE'] = 'verplaats';
$MOD_BAKERY['TXT_DUPLICATE'] = 'dupliceer';

$MOD_BAKERY['TXT_CART'] = 'Winkelwagen';
$MOD_BAKERY['TXT_ORDER'] = 'Bestelling';
$MOD_BAKERY['TXT_ORDER_ID'] = 'Bestelnummer';
$MOD_BAKERY['TXT_CONTINUE_SHOPPING'] = 'Doorgaan met winkelen';
$MOD_BAKERY['TXT_ADD_TO_CART'] = 'Aan winkelwagen toevoegen';
$MOD_BAKERY['TXT_VIEW_CART'] = 'Winkelwagen bekijken';
$MOD_BAKERY['TXT_UPDATE_CART'] = 'Ververs winkelwagen';
$MOD_BAKERY['TXT_UPDATE_CART_SUCCESS'] = 'Winkelwagen is ververst.';
$MOD_BAKERY['TXT_SUBMIT_ORDER'] = 'Verzend bestelling';
$MOD_BAKERY['TXT_SUBMIT_BUY'] = 'Submit order';
$MOD_BAKERY['TXT_QUIT_ORDER'] = 'Bestelling afbreken';
$MOD_BAKERY['TXT_ORDER_SUMMARY'] = 'Bestelling';

$MOD_BAKERY['TXT_ADDRESS'] = 'adres';
$MOD_BAKERY['TXT_MODIFY_ADDRESS'] = 'Adres aanpassen';
$MOD_BAKERY['TXT_FILL_IN_ADDRESS'] = 'Uw adres';
$MOD_BAKERY['TXT_SHIP_ADDRESS'] = 'Aflever adres';
$MOD_BAKERY['TXT_BACK_TO_CART'] = 'Back to Cart';
$MOD_BAKERY['TXT_ADD_SHIP_FORM'] = 'Aflever adres toevoegen';
$MOD_BAKERY['TXT_HIDE_SHIP_FORM'] = 'Verberg aflever adres';
$MOD_BAKERY['TXT_FILL_IN_SHIP_ADDRESS'] = 'Aflever adres';
$MOD_BAKERY['TXT_AGREE_TAC'] = 'I agree to the terms and conditions';
$MOD_BAKERY['TXT_AGREE_CANCELLATION'] = 'I took cognizance of my right of cancellation';
$MOD_BAKERY['TXT_AGREE_PRIVACY'] = 'I have read and accepted the terms of privacy';
$MOD_BAKERY['TXT_RIGHT_OF_CANCELLATION'] = 'You have the right to cancel you order within two weeks. For further information please see our terms and conditions.';
$MOD_BAKERY['TXT_CANCEL'] = 'Uw bestelling is afgebroken.';
$MOD_BAKERY['TXT_DELETED'] = 'Alle bestellingen zijn verwijderd.';
$MOD_BAKERY['TXT_THANK_U_VISIT'] = 'Dank u wel!';
$MOD_BAKERY['TXT_BACK_TO_SHOP'] = 'Back to Shop';

// MODULE BAKERY CUSTOMER DATA
$MOD_BAKERY['TXT_CUST_EMAIL'] = 'E-Mail adres';
$MOD_BAKERY['TXT_CUST_CONFIRM_EMAIL'] = 'Bevestig email adres';
$MOD_BAKERY['TXT_CUST_FIRST_NAME'] = 'Voornaam';
$MOD_BAKERY['TXT_CUST_LAST_NAME'] = 'Achternaam';
$MOD_BAKERY['TXT_CUST_ADDRESS'] = 'Adres';
$MOD_BAKERY['TXT_CUST_CITY'] = 'Plaats';
$MOD_BAKERY['TXT_CUST_STATE'] = 'Provincie';
$MOD_BAKERY['TXT_CUST_COUNTRY'] = 'Land';
$MOD_BAKERY['TXT_CUST_ZIP'] = 'Postcode';
$MOD_BAKERY['TXT_CUST_PHONE'] = 'Telefoon';

// MODULE BAKERY PROCESS PAYMENT
$MOD_BAKERY['TXT_CHECKOUT'] = 'Betaling';
$MOD_BAKERY['TXT_PAY_METHOD'] = 'Kies de gewenste betaalmethode';
$MOD_BAKERY['TXT_THANK_U_ORDER'] = 'Bedankt voor uw bestelling.';
$MOD_BAKERY['TXT_NO_PAYMENT_METHOD'] = 'Geen betaalmethode geactiveerd.';

// MODULE BAKERY ORDER ADMINISTRATION
$MOD_BAKERY['TXT_ORDER_ADMIN'] = 'Bestellingen Administratie';
$MOD_BAKERY['TXT_ORDER_ARCHIVED'] = 'Gearchiveerde Bestellingen';
$MOD_BAKERY['TXT_ORDER_CURRENT'] = 'Huidige Bestellingen';

$MOD_BAKERY['TXT_CUSTOMER'] = 'Klant';
$MOD_BAKERY['TXT_STATUS'] = 'Status';
$MOD_BAKERY['TXT_ORDER_DATE'] = 'Besteldatum';

$MOD_BAKERY['TXT_STATUS_ORDERED'] = 'besteld';
$MOD_BAKERY['TXT_STATUS_SHIPPED'] = 'verzonden';
$MOD_BAKERY['TXT_STATUS_BUSY'] = 'bezig met betaling';
$MOD_BAKERY['TXT_STATUS_INVOICE'] = 'Factuur';
$MOD_BAKERY['TXT_STATUS_REMINDER'] = 'Aanmaning';
$MOD_BAKERY['TXT_STATUS_PAID'] = 'betaald';
$MOD_BAKERY['TXT_STATUS_ARCHIVE'] = 'archiveren';
$MOD_BAKERY['TXT_STATUS_ARCHIVED'] = 'gearchiveerd';

$MOD_BAKERY['TXT_PRINT'] = 'Print';
$MOD_BAKERY['TXT_INVOICE'] = 'Factuur';
$MOD_BAKERY['TXT_DELIVERY_NOTE'] = 'Vrachtbrief';
$MOD_BAKERY['TXT_REMINDER'] = 'Aanmaning';
$MOD_BAKERY['TXT_PRINT_INVOICE'] = 'Print Factuur';

// MODULE BAKERY STOCK ADMINISTRATION
$MOD_BAKERY['TXT_STOCK_ADMIN'] = 'Voorraad Administratie';
$MOD_BAKERY['TXT_STOCK'] = 'Voorraad';
$MOD_BAKERY['TXT_IN_STOCK'] = 'in voorraad';
$MOD_BAKERY['TXT_SHORT_OF_STOCK'] = 'Weinig voorraad';
$MOD_BAKERY['TXT_OUT_OF_STOCK'] = 'Uitverkocht';
$MOD_BAKERY['TXT_NA'] = 'n/a';
$MOD_BAKERY['TXT_ALL'] = 'alle';
$MOD_BAKERY['TXT_ORDER_ASC'] = 'sorteer oplopend';
$MOD_BAKERY['TXT_ORDER_DESC'] = 'sorteer aflopend';
$MOD_BAKERY['TXT_SHORT_OF_STOCK_SUBSEQUENT_DELIVERY'] = 'Deze artikelen zijn onvoldoende in voorraad.<br />De ontbrekende artikelen zullen worden nagestuurd';
$MOD_BAKERY['TXT_SHORT_OF_STOCK_QUANTITY_CAPPED'] = 'Deze artikelen zijn onvoldoende in voorraad - het aantal is aangepast';
$MOD_BAKERY['TXT_AVAILABLE_QUANTITY'] = 'zijn beschikbaar';

// EDIT CSS BUTTON
$GLOBALS['TEXT']['CAP_EDIT_CSS'] = 'CSS Aanpassen';

// MODULE BAKERY ERROR MESSAGES (Important: Do not remove <br /> !)
$MOD_BAKERY['ERR_INVALID_FILE_NAME'] = 'De bestandsnaam is ongeldig';
$MOD_BAKERY['ERR_OFFLINE_TEXT'] = 'Deze winkel is momenteel offline voor onderhoud. Probeer later opnieuw.<br />Onze excuses voor het ongemak.';
$MOD_BAKERY['ERR_NO_ORDER_ID'] = 'Geen bestelnummer gevonden.';
$MOD_BAKERY['ERR_CART_EMPTY'] = 'De winkelwagen is leeg.'; 
$MOD_BAKERY['ERR_ITEM_EXISTS'] = 'U heeft dit produkt al in uw winkelwagen.<br />U kunt hieronder wel het aantal aanpassen.';
$MOD_BAKERY['ERR_QUANTITY_ZERO'] = 'Het aantal moet groter als nul zijn!';
$MOD_BAKERY['ERR_FIELD_BLANK'] = 'De rood gemarkeerde velden zijn leeg. Vul a.u.b. de gewenste informatie in!';
$MOD_BAKERY['ERR_EMAILS_NOT_MATCHED'] = 'De email adressen komen niet overeen!';
$MOD_BAKERY['ERR_INVAL_NAME'] = 'is niet een geldige naam.';
$MOD_BAKERY['ERR_INVAL_STREET'] = 'is niet een geldig adres.';
$MOD_BAKERY['ERR_INVAL_CITY'] = 'is niet een geldige plaatsnaam.';
$MOD_BAKERY['ERR_INVAL_STATE'] = 'is niet een geldige provincie.';
$MOD_BAKERY['ERR_INVAL_COUNTRY'] = 'is niet een geldig land.';
$MOD_BAKERY['ERR_INVAL_EMAIL'] = 'is niet een geldig email adres.';
$MOD_BAKERY['ERR_INVAL_ZIP'] = 'is niet een geldige postcode.';
$MOD_BAKERY['ERR_INVAL_PHONE'] = 'is niet een geldig telefoonnummer.';
$MOD_BAKERY['ERR_INVAL_TRY_AGAIN'] = 'Controleer uw invoer!';
$MOD_BAKERY['ERR_AGREE'] = 'We kunnen uw bestelling alleen uitvoeren als u akkoord gaat met onze leveringsvoorwaarden.<br />Bedankt voor uw begrip!';
$MOD_BAKERY['ERR_EMAIL_NOT_SENT'] = 'De email kon niet worden verzonden. Uw bestelling is nog steeds geldig.<br/>Neem contact op met de winkel beheerder';

// MODULE BAKERY JAVASCRIPT MESSAGES (Important: Do not remove \n !)
$MOD_BAKERY['TXT_JS_CONFIRM'] = 'Wilt u uw bestelling afbreken?';
$MOD_BAKERY['TXT_JS_AGREE_TAC'] = 'We kunnen uw bestelling alleen uitvoeren als u akkoord gaat met onze leveringsvoorwaarden.\nBedankt voor uw begrip!';
$MOD_BAKERY['TXT_JS_AGREE_CANCELLATION'] = 'We can only complete your order if you take cognizance of your right of cancellation.\nThank you for your understanding!';
$MOD_BAKERY['TXT_JS_AGREE_PRIVACY'] = 'We can only complete your order if you accept our terms of privady.\nThank you for your understanding!';
$MOD_BAKERY['TXT_JS_BLANK_CAPTCHA'] = 'Voer a.u.b. de controle code in!';
$MOD_BAKERY['TXT_JS_INCORRECT_CAPTCHA'] = 'De controlecode klopt niet.\nProbeer opnieuw!';



// If utf-8 is set as default charset convert some iso-8859-1 strings to utf-8 
if (defined('DEFAULT_CHARSET') && DEFAULT_CHARSET == 'utf-8') {
	$MOD_BAKERY['ADD_REGEXP_CHARS'] = utf8_encode($MOD_BAKERY['ADD_REGEXP_CHARS']);
}

?>