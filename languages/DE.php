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
$MOD_BAKERY['ADD_REGEXP_CHARS'] = 'ÄÖÜäöüß,';

// Webanalytics page filenames and titles
$MOD_BAKERY_FILENAME['shopping_cart'] = '/view_cart.php';
$MOD_BAKERY_FILENAME['address_form'] = '/view_form.php';
$MOD_BAKERY_FILENAME['order_summary'] = '/view_summary.php';
$MOD_BAKERY_FILENAME['payment_methods'] = '/view_pay.php';
$MOD_BAKERY_FILENAME['payment_error'] = '/view_confirmation_error.php';
$MOD_BAKERY_FILENAME['payment_cancelled'] = '/view_confirmation_cancel.php';
$MOD_BAKERY_FILENAME['payment_success'] = '/view_confirmation_success.php';

$_shop_name = isset($setting_shop_name) ? $setting_shop_name.' - ' : '';
$MOD_BAKERY_PAGETITLE['shopping_cart'] = $_shop_name.'Warenkorb';
$MOD_BAKERY_PAGETITLE['address_form'] = $_shop_name.'Adressformular';
$MOD_BAKERY_PAGETITLE['order_summary'] = $_shop_name.'Zusammenfassung';
$MOD_BAKERY_PAGETITLE['payment_methods'] = $_shop_name.'Zahlungsmethoden';
$MOD_BAKERY_PAGETITLE['payment_error'] = $_shop_name.'Fehler bei Bezahlung';
$MOD_BAKERY_PAGETITLE['payment_cancelled'] = $_shop_name.'Abbruch der Bezahlung';
$MOD_BAKERY_PAGETITLE['payment_success'] = $_shop_name.'Erfolgreiche Bezahlung';

// MODULE DESCRIPTION
$MODULEe_description = 'Bakery ist ein WebsiteBaker Shop Modul mit Katalog, Warenkorb, Lagerverwaltung und Auftragsverwaltung mit Rechnungsausdruck. Bezahlungsarten: Vorauszahlung, Rechnung und/oder verschiedene Zahlungsprovider. Weitere Informationen, Tipps und Tricks zu Bakery auf der <a href="http://www.bakery-shop.ch" target="_blank">Bakery Website</a> (Englisch).';

// MODULE BAKERY VARIOUS TEXT
$MOD_BAKERY['TXT_SETTINGS'] = 'Einstellungen';
$MOD_BAKERY['TXT_GENERAL_SETTINGS'] = 'Allgemeine Einstellungen';
$MOD_BAKERY['TXT_PAGE_SETTINGS'] = 'Seiten Einstellungen';
$MOD_BAKERY['TXT_PAYMENT_METHODS'] = 'Zahlungsmethoden';
$MOD_BAKERY['TXT_SHOP'] = 'Shop';
$MOD_BAKERY['TXT_PAYMENT'] = 'Zahlungs-';
$MOD_BAKERY['TXT_EMAIL'] = 'E-Mail';
$MOD_BAKERY['TXT_LAYOUT'] = 'Layout';
$MOD_BAKERY['TXT_PAGE_OFFLINE'] = 'Seite offline schalten';
$MOD_BAKERY['TXT_OFFLINE_TEXT'] = 'Offline Text';
$MOD_BAKERY['TXT_CONTINUE_URL'] = 'Einkauf fortsetzen URL';
$MOD_BAKERY['TXT_OVERVIEW'] = '&Uuml;bersicht';
$MOD_BAKERY['TXT_DETAIL'] = 'Detailansicht';
$MOD_BAKERY['TXT_SHOP_NAME'] = 'Shop Name';
$MOD_BAKERY['TXT_TAC_URL'] = 'AGB URL';
$MOD_BAKERY['TXT_CANCELLATION_URL'] = 'Widerruf URL';
$MOD_BAKERY['TXT_PRIVACY_URL'] = 'Datenschutz URL';
$MOD_BAKERY['TXT_SHOP_EMAIL'] = 'Shop E-Mail';
$MOD_BAKERY['TXT_SHOP_COUNTRY'] = 'Shop Land';
$MOD_BAKERY['TXT_SHOP_STATE'] = 'Shop Bundesland';
$MOD_BAKERY['TXT_ADDRESS_FORM'] = 'Adress-Formular';
$MOD_BAKERY['TXT_SHIPPING_FORM_REQUEST'] = 'zuschaltbar';
$MOD_BAKERY['TXT_SHIPPING_FORM_HIDEABLE'] = 'ausblendbar';
$MOD_BAKERY['TXT_SHIPPING_FORM_ALWAYS'] = 'immer';
$MOD_BAKERY['TXT_SHOW_STATE_FIELD'] = 'Zeige Feld Bundesland/Kanton';
$MOD_BAKERY['TXT_SHOW_ZIP_END_OF_ADDRESS'] = 'PLZ am Ende der Adresse';
$MOD_BAKERY['TXT_ALLOW_OUT_OF_STOCK_ORDERS'] = 'Verk&auml;ufe ohne ausreichenden Lagerbestand zulassen';
$MOD_BAKERY['TXT_SKIP_CART_AFTER_ADDING_ITEM'] = 'Nach dem Hinzugef&uuml;gen eines Artikels Warenkorb nicht anzeigen';
$MOD_BAKERY['TXT_MINICART_STRONGLY_RECOMMENDED'] = 'MiniCart dringend empfohlen';
$MOD_BAKERY['TXT_DISPLAY_SETTINGS_TO_ADMIN_ONLY'] = 'nur dem Admin mit der id = 1 zeigen';
$MOD_BAKERY['TXT_FREE_DEFINABLE_FIELD'] = 'Frei definierbares Feld';
$MOD_BAKERY['TXT_STOCK_MODE_TEXT'] = 'Lagerbestand den Kunden als Text zeigen';
$MOD_BAKERY['TXT_STOCK_MODE_IMAGE'] = 'Lagerbestand den Kunden als Bild zeigen';
$MOD_BAKERY['TXT_STOCK_MODE_NUMBER'] = 'Lagerbestand den Kunden als Zahl zeigen';
$MOD_BAKERY['TXT_STOCK_MODE_NONE'] = 'Lagerbestand den Kunden nicht zeigen';
$MOD_BAKERY['TXT_SHOP_CURRENCY'] = 'Shop W&auml;hrungscode';
$MOD_BAKERY['TXT_SEPARATOR_FOR'] = 'Trennzeichen f&uuml;r';
$MOD_BAKERY['TXT_DECIMAL'] = 'Dezimalstellen';
$MOD_BAKERY['TXT_GROUP_OF_THOUSANDS'] = 'Tausender-Gruppierung';

$MOD_BAKERY['TXT_PAYMENT_METHOD'] = 'Zahlungsmethode';
$MOD_BAKERY['TXT_SELECT_PAYMENT_METHODS'] = 'Zahlungsmethoden ausw&auml;hlen';
$MOD_BAKERY['TXT_PAYMENT_METHOD_COD'] = 'Nachnahme';
$MOD_BAKERY['TXT_PAYMENT_METHOD_ADVANCE'] = 'Vorauszahlung';
$MOD_BAKERY['TXT_PAYMENT_METHOD_INVOICE'] = 'Rechnung';
$MOD_BAKERY['TXT_PAYMENT_METHOD_PAYMENT_NETWORK'] = 'sofort&uuml;berweisung.de';
$MOD_BAKERY['TXT_SKIP_CHECKOUT'] = 'Kasse nicht zeigen, wenn nur 1 Zahlungsmethode ausgew&auml;hlt ist';
$MOD_BAKERY['TXT_NO_PAYMENT_METHOD_SETTING'] = 'F&uuml;r diese Zahlungsmethode muss keine Einstellung vorgenommen werden.';
$MOD_BAKERY['TXT_NOTICE'] = 'Hinweis';
$MOD_BAKERY['TXT_DAYS'] = 'Tage';

$MOD_BAKERY['TXT_TAX_RATE'] = 'USt-Satz';
$MOD_BAKERY['TXT_SAVED_TAX_RATE'] = 'Aktuell gespeicherter USt-Satz';
$MOD_BAKERY['TXT_SET_TAX_RATE'] = 'Setzen Sie einen USt-Satz fest';
$MOD_BAKERY['TXT_TAX_INCLUDED'] = 'Preise inkl. USt';
$MOD_BAKERY['TXT_TAX_EXCLUDED'] = 'Preise exkl. USt';
$MOD_BAKERY['TXT_TAX_FREE'] = 'Umsatzsteuerfrei';
$MOD_BAKERY['TXT_PLUS_SHIPPING'] = 'zzgl. Versandkosten';
$MOD_BAKERY['TXT_DOMESTIC'] = 'Inland';
$MOD_BAKERY['TXT_ZONE_COUNTRIES'] = 'in folgende L&auml;nder (Mehrfachauswahl m&ouml;glich)';
$MOD_BAKERY['TXT_ABROAD'] = 'Ausland';
$MOD_BAKERY['TXT_PER_ITEM'] = 'pro Artikel';
$MOD_BAKERY['TXT_SHIPPING_BASED_ON'] = 'Versandkosten';
$MOD_BAKERY['TXT_SHIPPING_METHOD_FLAT'] = 'pauschaler Betrag';
$MOD_BAKERY['TXT_SHIPPING_METHOD_ITEMS'] = 'mal Anzahl Artikel';
$MOD_BAKERY['TXT_SHIPPING_METHOD_POSITIONS'] = 'mal Anzahl Positionen';
$MOD_BAKERY['TXT_SHIPPING_METHOD_PERCENTAGE'] = 'Prozentsatz von Subtotal';
$MOD_BAKERY['TXT_SHIPPING_METHOD_HIGHEST'] = 'Artikel mit den h&ouml;chsten Versandkosten';
$MOD_BAKERY['TXT_SHIPPING_METHOD_NONE'] = 'keine';
$MOD_BAKERY['TXT_FREE_SHIPPING'] = 'Kostenloser Versand';
$MOD_BAKERY['TXT_OVER'] = 'ab';
$MOD_BAKERY['TXT_SHOW_FREE_SHIPPING_MSG'] = 'Kunden &uuml;ber kostenlosen Versand informieren';
$MOD_BAKERY['TXT_EMAIL_SUBJECT'] = 'E-Mail Betreff ';
$MOD_BAKERY['TXT_EMAIL_BODY'] = 'E-Mail Text';
$MOD_BAKERY['TXT_ITEM'] = 'Artikel';
$MOD_BAKERY['TXT_ITEMS'] = 'Artikel';
$MOD_BAKERY['TXT_ITEMS_PER_PAGE'] = 'Artikel pro Seite';
$MOD_BAKERY['TXT_NUMBER_OF_COLUMS'] = 'Anzahl Kolonnen';
$MOD_BAKERY['TXT_USE_CAPTCHA'] = 'Captcha ein';
$MOD_BAKERY['TXT_MODIFY_THIS'] = 'Die Seiteneinstellungen nur f&uuml;r <b>diese</b> Shop-Seite &uuml;bernehmen.';
$MOD_BAKERY['TXT_MODIFY_ALL'] = 'Die Seiteneinstellungen (ohne &quot;Einkauf fortsetzen URL&quot;) f&uuml;r <b>alle</b> Shop-Seiten &uuml;bernehmen.';
$MOD_BAKERY['TXT_MODIFY_MULTIPLE'] = 'Die Seiteneinstellungen (ohne &quot;Einkauf fortsetzen URL&quot;) nur f&uuml;r die <b>ausgew&auml;hlte(n)</b> Shop-Seite(n) &uuml;bernehmen (Mehrfachauswahl):';

$MOD_BAKERY['PREVIOUS_ITEM'] = '<< Voriger Artikel';
$MOD_BAKERY['ITEM_OVERVIEW'] = 'Zur Übersicht';
$MOD_BAKERY['NEXT_ITEM'] = 'Nächster Artikel >>';

$MOD_BAKERY['TXT_ADD_ITEM'] = 'Artikel hinzuf&uuml;gen';
$MOD_BAKERY['TXT_NAME'] = 'Bezeichnung';
$MOD_BAKERY['TXT_SKU'] = 'Art-Nr.';
$MOD_BAKERY['TXT_PRICE'] = 'Preis';
$MOD_BAKERY['TXT_OPTION_NAME'] = 'Option Name';
$MOD_BAKERY['TXT_OPTION_ATTRIBUTES'] = 'Option Werte';
$MOD_BAKERY['TXT_OPTION_PRICE'] = 'Optionspreis';
$MOD_BAKERY['TXT_ITEM_OPTIONS'] = 'Artikel Optionen';
$MOD_BAKERY['TXT_EG_OPTION_NAME'] = 'z.B. Farbe';
$MOD_BAKERY['TXT_EG_OPTION_ATTRIBUTE'] = 'z.B. rot';
$MOD_BAKERY['TXT_INCL'] = 'Inklusive';
$MOD_BAKERY['TXT_EXCL_SHIPPING_TAX'] = 'ohne Versandkosten und USt';
$MOD_BAKERY['TXT_TAX'] = 'USt';
$MOD_BAKERY['TXT_QUANTITY'] = 'Menge';
$MOD_BAKERY['TXT_SUM'] = 'Gesamt';
$MOD_BAKERY['TXT_SUBTOTAL'] = 'Zwischensumme';
$MOD_BAKERY['TXT_TOTAL'] = 'Gesamtsumme';
$MOD_BAKERY['TXT_SHIPPING'] = 'Versand';
$MOD_BAKERY['TXT_SHIPPING_COST'] = 'Versandkosten';
$MOD_BAKERY['TXT_DESCRIPTION'] = 'Kurzbeschreibung';
$MOD_BAKERY['TXT_CHARACTERISTICS'] = 'Wesentliche Produktmerkmale';
$MOD_BAKERY['TXT_FULL_DESC'] = 'Beschreibung';
$MOD_BAKERY['TXT_VIEW_ITEM_TEXTAREA'] = 'Mitteilungen';
$MOD_BAKERY['TXT_PREVIEW'] = 'Vorschau';
$MOD_BAKERY['TXT_FILE_NAME'] = 'Dateiname';
$MOD_BAKERY['TXT_MAIN_IMAGE'] = 'Hauptbild';
$MOD_BAKERY['TXT_THUMBNAIL'] = 'Vorschaubild';
$MOD_BAKERY['TXT_IMAGE'] = 'Bild';
$MOD_BAKERY['TXT_IMAGES'] = 'Bilder';
$MOD_BAKERY['TXT_MAX_WIDTH'] = 'max. Breite (px)';
$MOD_BAKERY['TXT_MAX_HEIGHT'] = 'max. H&ouml;he (px)';
$MOD_BAKERY['TXT_JPG_QUALITY'] = 'JPG Qualit&auml;t';
$MOD_BAKERY['TXT_NON'] = 'keines';
$MOD_BAKERY['TXT_ITEM_TO_PAGE'] = 'Artikel zur Seite';
$MOD_BAKERY['TXT_MOVE'] = 'verschieben';
$MOD_BAKERY['TXT_DUPLICATE'] = 'duplizieren';

$MOD_BAKERY['TXT_CART'] = 'Warenkorb';
$MOD_BAKERY['TXT_ORDER'] = 'Bestellung';
$MOD_BAKERY['TXT_ORDER_ID'] = 'Bestellnummer';
$MOD_BAKERY['TXT_CONTINUE_SHOPPING'] = 'Einkauf fortsetzen';
$MOD_BAKERY['TXT_ADD_TO_CART'] = 'In den Warenkorb';
$MOD_BAKERY['TXT_VIEW_CART'] = 'Warenkorb anzeigen';
$MOD_BAKERY['TXT_UPDATE_CART'] = 'Warenkorb aktualisieren';
$MOD_BAKERY['TXT_UPDATE_CART_SUCCESS'] = 'Der Warenkorb wurde erfolgreich aktualisiert.';
$MOD_BAKERY['TXT_SUBMIT_ORDER'] = 'Bestellung aufgeben';
$MOD_BAKERY['TXT_SUBMIT_BUY'] = 'Zahlungspflichtig kaufen';
$MOD_BAKERY['TXT_QUIT_ORDER'] = 'Bestellung abbrechen';
$MOD_BAKERY['TXT_ORDER_SUMMARY'] = 'Zusammenfassung der Bestellung';

$MOD_BAKERY['TXT_ADDRESS'] = 'Adresse';
$MOD_BAKERY['TXT_MODIFY_ADDRESS'] = 'Adresse bearbeiten';
$MOD_BAKERY['TXT_FILL_IN_ADDRESS'] = 'Bitte tragen Sie Ihre Adresse ein';
$MOD_BAKERY['TXT_SHIP_ADDRESS'] = 'Versandadresse';
$MOD_BAKERY['TXT_BACK_TO_CART'] = 'Zurück zum Warenkorb';
$MOD_BAKERY['TXT_ADD_SHIP_FORM'] = 'Abweichende Versandadresse';
$MOD_BAKERY['TXT_HIDE_SHIP_FORM'] = 'Versandadresse ausblenden';
$MOD_BAKERY['TXT_FILL_IN_SHIP_ADDRESS'] = 'Bitte tragen Sie die Versandadresse ein';
$MOD_BAKERY['TXT_AGREE_TAC'] = 'Ich akzeptiere die Allgmeinen Geschäftsbedingungen';
$MOD_BAKERY['TXT_AGREE_CANCELLATION'] = 'Die Widerrufsbelehrung habe ich zur Kenntnis genommen';
$MOD_BAKERY['TXT_AGREE_PRIVACY'] = 'Ich habe die Datenschutzbestimmungen gelesen und akzeptiert';
$MOD_BAKERY['TXT_RIGHT_OF_CANCELLATION'] = 'Sie haben per Gesetz ein zweiw&ouml;chiges Widerrufsrecht. Die Einzelheiten hierzu entnehmen Sie bitte unseren AGB.';
$MOD_BAKERY['TXT_CANCEL'] = 'Sie haben Ihre Bestellung abgebrochen.';
$MOD_BAKERY['TXT_DELETED'] = 'Ihre gesamten Daten wurden gel&ouml;scht.';
$MOD_BAKERY['TXT_THANK_U_VISIT'] = 'Besten Dank f&uuml;r Ihren Besuch und auf Wiedersehen!';
$MOD_BAKERY['TXT_BACK_TO_SHOP'] = 'Zurück zum Shop';

// MODULE BAKERY CUSTOMER DATA
$MOD_BAKERY['TXT_CUST_EMAIL'] = 'E-Mail Adresse';
$MOD_BAKERY['TXT_CUST_CONFIRM_EMAIL'] = 'E-Mail best&auml;tigen';
$MOD_BAKERY['TXT_CUST_FIRST_NAME'] = 'Vorname';
$MOD_BAKERY['TXT_CUST_LAST_NAME'] = 'Nachname';
$MOD_BAKERY['TXT_CUST_ADDRESS'] = 'Straße, Nummer';
$MOD_BAKERY['TXT_CUST_CITY'] = 'Ort';
$MOD_BAKERY['TXT_CUST_STATE'] = 'Bundesland/Kt.';
$MOD_BAKERY['TXT_CUST_COUNTRY'] = 'Land';
$MOD_BAKERY['TXT_CUST_ZIP'] = 'PLZ';
$MOD_BAKERY['TXT_CUST_PHONE'] = 'Telefonnummer';

// MODULE BAKERY PROCESS PAYMENT
$MOD_BAKERY['TXT_CHECKOUT'] = 'Kasse';
$MOD_BAKERY['TXT_PAY_METHOD'] = 'Bitte w&auml;hlen Sie Ihre Zahlungsmethode';
$MOD_BAKERY['TXT_THANK_U_ORDER'] = 'Besten Dank f&uuml;r Ihre Bestellung und auf Wiedersehen!';
$MOD_BAKERY['TXT_NO_PAYMENT_METHOD'] = 'Es wurde keine Zahlungsmethode aktiviert.';

// MODULE BAKERY ORDER ADMINISTRATION
$MOD_BAKERY['TXT_ORDER_ADMIN'] = 'Auftragsverwaltung';
$MOD_BAKERY['TXT_ORDER_ARCHIVED'] = 'Alle archivierten Bestellungen';
$MOD_BAKERY['TXT_ORDER_CURRENT'] = 'Alle aktuellen Bestellungen';

$MOD_BAKERY['TXT_CUSTOMER'] = 'Kunde';
$MOD_BAKERY['TXT_STATUS'] = 'Status';
$MOD_BAKERY['TXT_ORDER_DATE'] = 'Bestelldatum';

$MOD_BAKERY['TXT_STATUS_ORDERED'] = 'bestellt';
$MOD_BAKERY['TXT_STATUS_SHIPPED'] = 'versandt';
$MOD_BAKERY['TXT_STATUS_BUSY'] = 'Zahlung in Bearbeitung';
$MOD_BAKERY['TXT_STATUS_INVOICE'] = 'Rechnung';
$MOD_BAKERY['TXT_STATUS_REMINDER'] = 'Zahlungserinnerung';
$MOD_BAKERY['TXT_STATUS_PAID'] = 'bezahlt';
$MOD_BAKERY['TXT_STATUS_ARCHIVE'] = 'archivieren';
$MOD_BAKERY['TXT_STATUS_ARCHIVED'] = 'archiviert';

$MOD_BAKERY['TXT_PRINT'] = 'Drucken';
$MOD_BAKERY['TXT_INVOICE'] = 'Rechnung';
$MOD_BAKERY['TXT_DELIVERY_NOTE'] = 'Lieferschein';
$MOD_BAKERY['TXT_REMINDER'] = 'Zahlungserinnerung';
$MOD_BAKERY['TXT_PRINT_INVOICE'] = 'Rechnung drucken';

// MODULE BAKERY STOCK ADMINISTRATION
$MOD_BAKERY['TXT_STOCK_ADMIN'] = 'Lagerverwaltung';
$MOD_BAKERY['TXT_STOCK'] = 'Lagerbestand';
$MOD_BAKERY['TXT_IN_STOCK'] = 'an Lager';
$MOD_BAKERY['TXT_SHORT_OF_STOCK'] = 'nur noch wenige';
$MOD_BAKERY['TXT_OUT_OF_STOCK'] = 'ausverkauft';
$MOD_BAKERY['TXT_NA'] = 'k.A.';
$MOD_BAKERY['TXT_ALL'] = 'alle';
$MOD_BAKERY['TXT_ORDER_ASC'] = 'aufsteigend sortieren';
$MOD_BAKERY['TXT_ORDER_DESC'] = 'absteigend sortieren';
$MOD_BAKERY['TXT_SHORT_OF_STOCK_SUBSEQUENT_DELIVERY'] = 'Die Liefermenge folgender Artikel ist beschr&auml;nkt.<br />Fehlende Artikel werden Ihnen nachgeliefert';
$MOD_BAKERY['TXT_SHORT_OF_STOCK_QUANTITY_CAPPED'] = 'Die Liefermenge dieser Artikel ist beschr&auml;nkt - Ihre Bestellmenge wurde angepasst';
$MOD_BAKERY['TXT_AVAILABLE_QUANTITY'] = 'sind noch verf&uuml;gbar';

// EDIT CSS BUTTON
$GLOBALS['TEXT']['CAP_EDIT_CSS'] = 'CSS bearbeiten';

// MODULE BAKERY ERROR MESSAGES (Important: Do not remove <br /> !)
$MOD_BAKERY['ERR_INVALID_FILE_NAME'] = 'Der Dateiname ist ung&uuml;ltig';
$MOD_BAKERY['ERR_OFFLINE_TEXT'] = 'Diese Shop Seite wird zur Zeit gewartet und ist offline. Bitte kommen Sie sp&auml;ter noch einmal vorbei. <br />F&uuml;r diese Unannehmlichkeit bitten wir um Verzeihung.';
$MOD_BAKERY['ERR_NO_ORDER_ID'] = 'Keine Bestellnummer gefunden.';
$MOD_BAKERY['ERR_CART_EMPTY'] = 'Der Warenkorb ist leer.';
$MOD_BAKERY['ERR_ITEM_EXISTS'] = 'Dieser Artikel befindet sich schon in Ihrem Warenkorb.<br />Sie k&ouml;nnen die Bestellmenge direkt hier im Warenkorb ver&auml;ndern.';
$MOD_BAKERY['ERR_QUANTITY_ZERO'] = 'Die Bestellmenge muss eine Zahl und gr&ouml;sser als Null sein!';
$MOD_BAKERY['ERR_FIELD_BLANK'] = 'Die rot hinterlegten Felder sind leer. Bitte geben Sie die erforderlichen Daten ein!';
$MOD_BAKERY['ERR_EMAILS_NOT_MATCHED'] = 'Die E-Mail Adressen stimmen nicht &uuml;berein!';
$MOD_BAKERY['ERR_INVAL_NAME'] = 'ist kein g&uuml;ltiger Name.';
$MOD_BAKERY['ERR_INVAL_STREET'] = 'ist keine g&uuml;ltige Straße.';
$MOD_BAKERY['ERR_INVAL_CITY'] = 'ist keine g&uuml;ltige Stadt.';
$MOD_BAKERY['ERR_INVAL_STATE'] = 'ist kein g&uuml;ltiges Bundesland/Kanton.';
$MOD_BAKERY['ERR_INVAL_COUNTRY'] = 'ist kein g&uuml;ltiges Land.';
$MOD_BAKERY['ERR_INVAL_EMAIL'] = 'ist keine g&uuml;ltige E-Mail Adresse.';
$MOD_BAKERY['ERR_INVAL_ZIP'] = 'ist keine g&uuml;ltige PLZ.';
$MOD_BAKERY['ERR_INVAL_PHONE'] = 'ist keine g&uuml;ltige Telefonnummer.';
$MOD_BAKERY['ERR_INVAL_TRY_AGAIN'] = 'Bitte &uuml;berpr&uuml;fen Sie Ihre Eingabe(n)!';
$MOD_BAKERY['ERR_AGREE'] = 'Wir k&ouml;nnen Ihre Bestellung nur ausf&uuml;hren, wenn Sie unsere Geschäftsbedingungen und Verbraucherhinweise vollständig akzeptieren.<br />Hierf&uuml;r bitten wir um Ihr Verst&auml;ndnis!';
$MOD_BAKERY['ERR_EMAIL_NOT_SENT'] = 'Die E-Mail konnte nicht an Sie versandt werden. Ihre Bestellung ist dennoch g&uuml;ltig.<br />Bitte wenden Sie sich an den Shop-Betreiber';

// MODULE BAKERY JAVASCRIPT MESSAGES (Important: Do not remove \n !)
$MOD_BAKERY['TXT_JS_CONFIRM'] = 'M&ouml;chten Sie Ihre Bestellung wirklich abbrechen?';
$MOD_BAKERY['TXT_JS_AGREE_TAC'] = 'Wir k&ouml;nnen Ihre Bestellung nur ausf&uuml;hren, wenn Sie unsere AGB akzeptieren.\nDanke f&uuml;r Ihr Verst&auml;ndnis!';
$MOD_BAKERY['TXT_JS_AGREE_CANCELLATION'] = 'Wir k&ouml;nnen Ihre Bestellung nur ausf&uuml;hren, wenn Sie unsere Widerrufsbedingungen akzeptieren.\nDanke f&uuml;r Ihr Verst&auml;ndnis!';
$MOD_BAKERY['TXT_JS_AGREE_PRIVACY'] = 'Wir k&ouml;nnen Ihre Bestellung nur ausf&uuml;hren, wenn Sie unsere Datenschutzbestimmungen akzeptieren.\nDanke f&uuml;r Ihr Verst&auml;ndnis!';
$MOD_BAKERY['TXT_JS_BLANK_CAPTCHA'] = 'Bitte geben Sie die Pr&uuml;fziffer ein!';
$MOD_BAKERY['TXT_JS_INCORRECT_CAPTCHA'] = 'Die eingegebene Pr&uuml;fziffer stimmt nicht &uuml;berein.\nBitte &uuml;berpr&uuml;fen Sie Ihre Eingabe!';



// If utf-8 is set as default charset convert some iso-8859-1 strings to utf-8 
if (defined('DEFAULT_CHARSET') && DEFAULT_CHARSET == 'utf-8') {
	$MOD_BAKERY['ADD_REGEXP_CHARS'] = utf8_encode($MOD_BAKERY['ADD_REGEXP_CHARS']);
}

?>