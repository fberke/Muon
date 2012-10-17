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
$MOD_BAKERY['ADD_REGEXP_CHARS'] = 'ÇçÀàâÉéèêëÎîïôûù°';

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
$module_description = 'Bakery est un module de boutique pour Website Baker comportant un syst&egrave;me de panier, un catalogue, une gestion du stock et des commandes et une &eacute;dition de factures. Diff&eacute;rentes plates formes pour des syst&egrave;mes de paiements sont inclus, ainsi que la facturation et le paiement anticip&eacute;. Vous trouverez plus d&apos;information (en anglais) sur le <a href="http://www.bakery-shop.ch" target="_blank">Site Web de Bakery</a>.';

// MODULE BAKERY VARIOUS TEXT
$MOD_BAKERY['TXT_SETTINGS'] = 'R&eacute;glages';
$MOD_BAKERY['TXT_GENERAL_SETTINGS'] = 'R&eacute;glages G&eacute;n&eacute;raux';
$MOD_BAKERY['TXT_PAGE_SETTINGS'] = 'R&eacute;glages de la Page';
$MOD_BAKERY['TXT_PAYMENT_METHODS'] = 'M&eacute;thodes de Paiement';
$MOD_BAKERY['TXT_SHOP'] = 'Boutique';
$MOD_BAKERY['TXT_PAYMENT'] = 'Paiement';
$MOD_BAKERY['TXT_EMAIL'] = 'Email';
$MOD_BAKERY['TXT_LAYOUT'] = 'Mise en Page';
$MOD_BAKERY['TXT_PAGE_OFFLINE'] = 'Afficher la page &quot;hors ligne&quot;';
$MOD_BAKERY['TXT_OFFLINE_TEXT'] = 'Texte quand &quot;hors ligne&quot;';
$MOD_BAKERY['TXT_CONTINUE_URL'] = 'URL &quot;Retourner &agrave; la Boutique&quot;';
$MOD_BAKERY['TXT_OVERVIEW'] = 'Vue d&apos;ensemble';
$MOD_BAKERY['TXT_DETAIL'] = 'D&eacute;tail de L&apos;Article';
$MOD_BAKERY['TXT_SHOP_NAME'] = 'Nom de la Boutique';
$MOD_BAKERY['TXT_TAC_URL'] = 'URL des Conditions G&eacute;n&eacute;rales';
$MOD_BAKERY['TXT_CANCELLATION_URL'] = 'Cancellation URL';
$MOD_BAKERY['TXT_PRIVACY_URL'] = 'Privacy URL';
$MOD_BAKERY['TXT_SHOP_EMAIL'] = 'Adresse email de la Boutique';
$MOD_BAKERY['TXT_SHOP_COUNTRY'] = 'Pays de la Boutique';
$MOD_BAKERY['TXT_SHOP_STATE'] = 'R&eacute;gion de la Boutique';
$MOD_BAKERY['TXT_ADDRESS_FORM'] = 'Formulaire d&apos;Adresse';
$MOD_BAKERY['TXT_SHIPPING_FORM_REQUEST'] = 'sur demande';
$MOD_BAKERY['TXT_SHIPPING_FORM_HIDEABLE'] = 'd&eacute;sactivable';
$MOD_BAKERY['TXT_SHIPPING_FORM_ALWAYS'] = 'toujours';
$MOD_BAKERY['TXT_SHOW_STATE_FIELD'] = 'Demander la r&eacute;gion';
$MOD_BAKERY['TXT_SHOW_ZIP_END_OF_ADDRESS'] = 'Code Postal en fin d&apos;Adresse';
$MOD_BAKERY['TXT_ALLOW_OUT_OF_STOCK_ORDERS'] = 'Permettre les commandes Hors Stock';
$MOD_BAKERY['TXT_SKIP_CART_AFTER_ADDING_ITEM'] = 'Ne pas afficher le panier apr&egrave;s l&apos;ajout d&apos;un article';
$MOD_BAKERY['TXT_MINICART_STRONGLY_RECOMMENDED'] = 'MiniCart fortement recommend&eacute;';
$MOD_BAKERY['TXT_DISPLAY_SETTINGS_TO_ADMIN_ONLY'] = 'Afficher les r&eacute;glages seulement pour l&apos;admin (id = 1)';
$MOD_BAKERY['TXT_FREE_DEFINABLE_FIELD'] = 'Champ Librement D&eacute;finissable';
$MOD_BAKERY['TXT_STOCK_MODE_TEXT'] = 'L&apos;affichage du Stock pour le client se fait par Texte';
$MOD_BAKERY['TXT_STOCK_MODE_IMAGE'] = 'L&apos;affichage du Stock pour le client se fait par Image';
$MOD_BAKERY['TXT_STOCK_MODE_NUMBER'] = 'L&apos;affichage du Stock pour le client se fait par Nombre';
$MOD_BAKERY['TXT_STOCK_MODE_NONE'] = 'L&apos;affichage du Stock pour le client d&eacute;sactiv&eacute;';
$MOD_BAKERY['TXT_SHOP_CURRENCY'] = 'Afficher le Code de la Devise';
$MOD_BAKERY['TXT_SEPARATOR_FOR'] = 'Separateur pour';
$MOD_BAKERY['TXT_DECIMAL'] = 'D&eacute;cimal';
$MOD_BAKERY['TXT_GROUP_OF_THOUSANDS'] = 'Groupe de Milliers';

$MOD_BAKERY['TXT_PAYMENT_METHOD'] = 'M&eacute;thode de Paiement';
$MOD_BAKERY['TXT_SELECT_PAYMENT_METHODS'] = 'S&eacute;lectionner les M&eacute;thodes de Paiement';
$MOD_BAKERY['TXT_PAYMENT_METHOD_COD'] = 'Contre Remboursement';
$MOD_BAKERY['TXT_PAYMENT_METHOD_ADVANCE'] = 'Paiement anticip&eacute;';
$MOD_BAKERY['TXT_PAYMENT_METHOD_INVOICE'] = 'Facture';
$MOD_BAKERY['TXT_PAYMENT_METHOD_PAYMENT_NETWORK'] = 'DIRECTebanking.com';
$MOD_BAKERY['TXT_SKIP_CHECKOUT'] = 'Ne pas afficher &quot;Passer la commande&quot; quand 1 seule M&eacute;thode de Paiement est s&eacute;lectionn&eacute;e';
$MOD_BAKERY['TXT_NO_PAYMENT_METHOD_SETTING'] = 'Cette M&eacute;thode de Paiement ne n&eacute;cessite aucun R&eacute;glage.';
$MOD_BAKERY['TXT_NOTICE'] = 'Remarque';
$MOD_BAKERY['TXT_DAYS'] = 'Jours';

$MOD_BAKERY['TXT_TAX_RATE'] = 'Taux de Taxe';
$MOD_BAKERY['TXT_SAVED_TAX_RATE'] = 'Taux de Taxe courant sauvegard&eacute;';
$MOD_BAKERY['TXT_SET_TAX_RATE'] = 'D&eacute;finir le Taux de Taxe';
$MOD_BAKERY['TXT_TAX_INCLUDED'] = 'Prix T.T.C. (Toutes Taxes Comprises)';
$MOD_BAKERY['TXT_TAX_EXCLUDED'] = 'Prices excl. VAT';
$MOD_BAKERY['TXT_TAX_FREE'] = 'Sans TVA';
$MOD_BAKERY['TXT_PLUS_SHIPPING'] = 'plus shipping';
$MOD_BAKERY['TXT_DOMESTIC'] = 'nationale';
$MOD_BAKERY['TXT_ZONE_COUNTRIES'] = 'sp&eacute;cifique selon Pays (Choix)';
$MOD_BAKERY['TXT_ABROAD'] = '&agrave; l&apos;&eacute;tranger';
$MOD_BAKERY['TXT_PER_ITEM'] = 'par Article';
$MOD_BAKERY['TXT_SHIPPING_BASED_ON'] = 'Frais de Ports Calcul&eacute;s selon';
$MOD_BAKERY['TXT_SHIPPING_METHOD_FLAT'] = 'un montant forfaitaire';
$MOD_BAKERY['TXT_SHIPPING_METHOD_ITEMS'] = 'Nombre d&apos;Articles';
$MOD_BAKERY['TXT_SHIPPING_METHOD_POSITIONS'] = 'Nombre de Positions';
$MOD_BAKERY['TXT_SHIPPING_METHOD_PERCENTAGE'] = 'Pourcentage du Soustotal';
$MOD_BAKERY['TXT_SHIPPING_METHOD_HIGHEST'] = 'Article avec les Frais de Ports les plus &eacute;lev&eacute;s';
$MOD_BAKERY['TXT_SHIPPING_METHOD_NONE'] = 'aucun';
$MOD_BAKERY['TXT_FREE_SHIPPING'] = 'Livraison Gratuite';
$MOD_BAKERY['TXT_OVER'] = 'au dessus de';
$MOD_BAKERY['TXT_SHOW_FREE_SHIPPING_MSG'] = 'Informer les Clients &agrave; propos du montant permettant la Livraison Gratuite';
$MOD_BAKERY['TXT_EMAIL_SUBJECT'] = 'Email Sujet';
$MOD_BAKERY['TXT_EMAIL_BODY'] = 'Email Texte';
$MOD_BAKERY['TXT_ITEM'] = 'Article';
$MOD_BAKERY['TXT_ITEMS'] = 'Articles';
$MOD_BAKERY['TXT_ITEMS_PER_PAGE'] = 'Articles par Page';
$MOD_BAKERY['TXT_NUMBER_OF_COLUMS'] = 'Nombre de Colonnes';
$MOD_BAKERY['TXT_USE_CAPTCHA'] = 'Utiliser le &quot;CAPTCHA&quot;';
$MOD_BAKERY['TXT_MODIFY_THIS'] = 'Mettre &agrave; jour les R&eacute;glages de la Page seulement pour la Page <b>courante</b> Bakery.';
$MOD_BAKERY['TXT_MODIFY_ALL'] = 'Mettre &agrave; jour les R&eacute;glages de la Page (Sans l&apos;URL &quot;Retourner à la Boutique&quot;) pour <b>toutes</b> les Pages de la Boutique.';
$MOD_BAKERY['TXT_MODIFY_MULTIPLE'] = 'Mettre &agrave; jour les R&eacute;glages de la Page (Sans l&apos;URL &quot;Retourner à la Boutique&quot;) seulement pour les Pages <b>S&eacute;lectionn&eacute;es</b> (Utilisez la liste suivante avec possibilit&eacute; de Choix Multiples):';

$MOD_BAKERY['PREVIOUS_ITEM'] = '<< Previous Item';
$MOD_BAKERY['ITEM_OVERVIEW'] = 'Item Overview';
$MOD_BAKERY['NEXT_ITEM'] = 'Next Item >>';

$MOD_BAKERY['TXT_ADD_ITEM'] = 'Ajouter un Article';
$MOD_BAKERY['TXT_NAME'] = 'Nom de l&apos;Article';
$MOD_BAKERY['TXT_SKU'] = 'Code Interne';
$MOD_BAKERY['TXT_PRICE'] = 'Prix';
$MOD_BAKERY['TXT_OPTION_NAME'] = 'Nom de l&apos;Option';
$MOD_BAKERY['TXT_OPTION_ATTRIBUTES'] = 'Attributs de l&apos;Option';
$MOD_BAKERY['TXT_OPTION_PRICE'] = 'Prix de l&apos;Option';
$MOD_BAKERY['TXT_ITEM_OPTIONS'] = 'Options des Articles';
$MOD_BAKERY['TXT_EG_OPTION_NAME'] = 'ex: couleur';
$MOD_BAKERY['TXT_EG_OPTION_ATTRIBUTE'] = 'ex: rouge';
$MOD_BAKERY['TXT_INCL'] = 'Inclusif';
$MOD_BAKERY['TXT_EXCL_SHIPPING_TAX'] = 'Hors Taxe';
$MOD_BAKERY['TXT_TAX'] = 'Taxe';
$MOD_BAKERY['TXT_QUANTITY'] = 'Quantit&eacute;';
$MOD_BAKERY['TXT_SUM'] = 'Somme';
$MOD_BAKERY['TXT_SUBTOTAL'] = 'Sous-total';
$MOD_BAKERY['TXT_TOTAL'] = 'Total';
$MOD_BAKERY['TXT_SHIPPING'] = 'Livraison';
$MOD_BAKERY['TXT_SHIPPING_COST'] = 'Frais de Ports';
$MOD_BAKERY['TXT_DESCRIPTION'] = 'Description Courte';
$MOD_BAKERY['TXT_CHARACTERISTICS'] = 'Main Product Characteristics';
$MOD_BAKERY['TXT_FULL_DESC'] = 'Description Longue';
$MOD_BAKERY['TXT_VIEW_ITEM_TEXTAREA'] = 'Notification';
$MOD_BAKERY['TXT_PREVIEW'] = 'Pr&eacute;visualisation';
$MOD_BAKERY['TXT_FILE_NAME'] = 'Nom de Fichier';
$MOD_BAKERY['TXT_MAIN_IMAGE'] = 'Image Principale';
$MOD_BAKERY['TXT_THUMBNAIL'] = 'Vignette';
$MOD_BAKERY['TXT_IMAGE'] = 'Image';
$MOD_BAKERY['TXT_IMAGES'] = 'Images';
$MOD_BAKERY['TXT_MAX_WIDTH'] = 'Largeur max. (px)';
$MOD_BAKERY['TXT_MAX_HEIGHT'] = 'Hauteur max. (px)';
$MOD_BAKERY['TXT_JPG_QUALITY'] = 'Qualit&eacute; du JPG';
$MOD_BAKERY['TXT_NON'] = 'non';
$MOD_BAKERY['TXT_ITEM_TO_PAGE'] = 'D&eacute;placer l&apos;Article sur la Page';
$MOD_BAKERY['TXT_MOVE'] = 'd&eacute;placer';
$MOD_BAKERY['TXT_DUPLICATE'] = 'dupliquer';

$MOD_BAKERY['TXT_CART'] = 'Panier';
$MOD_BAKERY['TXT_ORDER'] = 'Commande';
$MOD_BAKERY['TXT_ORDER_ID'] = 'Commande n&deg;';
$MOD_BAKERY['TXT_CONTINUE_SHOPPING'] = 'Continuer mes achats';
$MOD_BAKERY['TXT_ADD_TO_CART'] = 'Ajouter au panier';
$MOD_BAKERY['TXT_VIEW_CART'] = 'Voir le panier';
$MOD_BAKERY['TXT_UPDATE_CART'] = 'Mettre &agrave; jour le panier';
$MOD_BAKERY['TXT_UPDATE_CART_SUCCESS'] = 'Panier mis &agrave; jour avec succ&egrave;s.';
$MOD_BAKERY['TXT_SUBMIT_ORDER'] = 'Passer la commande';
$MOD_BAKERY['TXT_SUBMIT_BUY'] = 'Submit order';
$MOD_BAKERY['TXT_QUIT_ORDER'] = 'Arr&ecirc;ter la commande';
$MOD_BAKERY['TXT_ORDER_SUMMARY'] = 'R&eacute;sum&eacute; de la commande';

$MOD_BAKERY['TXT_ADDRESS'] = 'Adresse';
$MOD_BAKERY['TXT_MODIFY_ADDRESS'] = 'Modifier l&apos;Adresse';
$MOD_BAKERY['TXT_FILL_IN_ADDRESS'] = 'Veuillez saisir votre adresse';
$MOD_BAKERY['TXT_SHIP_ADDRESS'] = 'Adresse de Livraison';
$MOD_BAKERY['TXT_BACK_TO_CART'] = 'Back to Cart';
$MOD_BAKERY['TXT_ADD_SHIP_FORM'] = 'Ajouter l&apos;Adresse de Livraison';
$MOD_BAKERY['TXT_HIDE_SHIP_FORM'] = 'Cacher l&apos;Adresse de Livraison';
$MOD_BAKERY['TXT_FILL_IN_SHIP_ADDRESS'] = 'Veuillez saisir votre adresse de Livraison';
$MOD_BAKERY['TXT_AGREE_TAC'] = 'I agree to the terms and conditions';
$MOD_BAKERY['TXT_AGREE_CANCELLATION'] = 'I took cognizance of my right of cancellation';
$MOD_BAKERY['TXT_AGREE_PRIVACY'] = 'I have read and accepted the terms of privacy';
$MOD_BAKERY['TXT_RIGHT_OF_CANCELLATION'] = 'You have the right to cancel you order within two weeks. For further information please see our terms and conditions.';
$MOD_BAKERY['TXT_CANCEL'] = 'Vous avez annul&eacute; votre commande.';
$MOD_BAKERY['TXT_DELETED'] = 'Toutes les donn&eacute;es vous concernant ont été effac&eacute;es.';
$MOD_BAKERY['TXT_THANK_U_VISIT'] = 'Merci pour votre visite et bonne journ&eacute;e!';
$MOD_BAKERY['TXT_BACK_TO_SHOP'] = 'Back to Shop';

// MODULE BAKERY CUSTOMER DATA
$MOD_BAKERY['TXT_CUST_EMAIL'] = 'Adresse email';
$MOD_BAKERY['TXT_CUST_CONFIRM_EMAIL'] = 'Confirmer l&apos;Adresse email';
$MOD_BAKERY['TXT_CUST_FIRST_NAME'] = 'Pr&eacute;nom';
$MOD_BAKERY['TXT_CUST_LAST_NAME'] = 'Nom';
$MOD_BAKERY['TXT_CUST_ADDRESS'] = 'Adresse';
$MOD_BAKERY['TXT_CUST_CITY'] = 'Ville';
$MOD_BAKERY['TXT_CUST_STATE'] = 'D&eacute;partement';
$MOD_BAKERY['TXT_CUST_COUNTRY'] = 'Pays';
$MOD_BAKERY['TXT_CUST_ZIP'] = 'Code Postal';
$MOD_BAKERY['TXT_CUST_PHONE'] = 'T&eacute;l&eacute;phone';

// MODULE BAKERY PROCESS PAYMENT
$MOD_BAKERY['TXT_CHECKOUT'] = 'R&egrave;glement de la commande';
$MOD_BAKERY['TXT_PAY_METHOD'] = 'Veuillez s&eacute;lectionner une m&eacute;thode de paiement';
$MOD_BAKERY['TXT_THANK_U_ORDER'] = 'Merci pour votre commande!';
$MOD_BAKERY['TXT_NO_PAYMENT_METHOD'] = 'Aucune M&eacute;thode de Paiement Activ&eacute;e.';

// MODULE BAKERY ORDER ADMINISTRATION
$MOD_BAKERY['TXT_ORDER_ADMIN'] = 'Gestion des Commandes';
$MOD_BAKERY['TXT_ORDER_ARCHIVED'] = 'Commandes Archiv&eacute;es';
$MOD_BAKERY['TXT_ORDER_CURRENT'] = 'Commandes en Cours';

$MOD_BAKERY['TXT_CUSTOMER'] = 'Client';
$MOD_BAKERY['TXT_STATUS'] = 'Etat';
$MOD_BAKERY['TXT_ORDER_DATE'] = 'Date de la Commande';

$MOD_BAKERY['TXT_STATUS_ORDERED'] = 'commande pass&eacute;e';
$MOD_BAKERY['TXT_STATUS_SHIPPED'] = 'livr&eacute;';
$MOD_BAKERY['TXT_STATUS_BUSY'] = 'Paiement en cours';
$MOD_BAKERY['TXT_STATUS_INVOICE'] = 'Facture';
$MOD_BAKERY['TXT_STATUS_REMINDER'] = 'Rappel';
$MOD_BAKERY['TXT_STATUS_PAID'] = 'pay&eacute;e';
$MOD_BAKERY['TXT_STATUS_ARCHIVE'] = 'archive';
$MOD_BAKERY['TXT_STATUS_ARCHIVED'] = 'archiv&eacute;e';

$MOD_BAKERY['TXT_PRINT'] = 'Imprimer';
$MOD_BAKERY['TXT_INVOICE'] = 'Facture';
$MOD_BAKERY['TXT_DELIVERY_NOTE'] = 'D&eacute;tail concernant la livraison';
$MOD_BAKERY['TXT_REMINDER'] = 'Rappel';
$MOD_BAKERY['TXT_PRINT_INVOICE'] = 'Imprimer la facture';

// MODULE BAKERY STOCK ADMINISTRATION
$MOD_BAKERY['TXT_STOCK_ADMIN'] = 'Gestion de Stock';
$MOD_BAKERY['TXT_STOCK'] = 'Stock';
$MOD_BAKERY['TXT_IN_STOCK'] = 'en Stock';
$MOD_BAKERY['TXT_SHORT_OF_STOCK'] = 'Stock R&eacute;duit';
$MOD_BAKERY['TXT_OUT_OF_STOCK'] = 'Plus en Stock';
$MOD_BAKERY['TXT_NA'] = 'n/d';
$MOD_BAKERY['TXT_ALL'] = 'tous';
$MOD_BAKERY['TXT_ORDER_ASC'] = 'ordre ascendant';
$MOD_BAKERY['TXT_ORDER_DESC'] = 'ordre descendant';
$MOD_BAKERY['TXT_SHORT_OF_STOCK_SUBSEQUENT_DELIVERY'] = 'Un de vos article est en stock r&eacute;duit.<br />Le d&eacute;lais de livraison peut subir des modifications';
$MOD_BAKERY['TXT_SHORT_OF_STOCK_QUANTITY_CAPPED'] = 'Un de vos article est en stock r&eacute;duit - la quantit&eacute; viens d&apos;&ecirc;tre ajust&eacute;e';
$MOD_BAKERY['TXT_AVAILABLE_QUANTITY'] = 'encore disponible';

// EDIT CSS BUTTON
$GLOBALS['TEXT']['CAP_EDIT_CSS'] = 'Editer la feuille CSS';

// MODULE BAKERY ERROR MESSAGES (Important: Do not remove <br /> !)
$MOD_BAKERY['ERR_INVALID_FILE_NAME'] = 'Nom de fichier invalide';
$MOD_BAKERY['ERR_OFFLINE_TEXT'] = 'Cette Page de la Boutique est Hors Ligne pour maintenance. Veuillez renouveler votre visite ult&eacute;rieurement.<br />Nous vous prions de nous excuser pour ce d&eacute;sagement.';
$MOD_BAKERY['ERR_NO_ORDER_ID'] = 'Code Interne non trouv&eacute;.';
$MOD_BAKERY['ERR_CART_EMPTY'] = 'Le panier est vide.'; 
$MOD_BAKERY['ERR_ITEM_EXISTS'] = 'Cet article est d&eacute;j&agrave; dans votre panier.<br /> Veuillez changer la quantit&eacute; dans le panier.';
$MOD_BAKERY['ERR_QUANTITY_ZERO'] = 'La quantit&eacute; doit &ecirc;tre un nombre sup&eacute;rieur &agrave; z&eacute;ro!';
$MOD_BAKERY['ERR_FIELD_BLANK'] = 'Les champs surlign&eacute;s en rouge sont vide. Veuillez entrer les informations requises!';
$MOD_BAKERY['ERR_EMAILS_NOT_MATCHED'] = 'Les adresses email n&apos;ont pas correspondu!';
$MOD_BAKERY['ERR_INVAL_NAME'] = 'n&apos;est pas un nom valide.';
$MOD_BAKERY['ERR_INVAL_STREET'] = 'n&apos;est pas une adresse valide.';
$MOD_BAKERY['ERR_INVAL_CITY'] = 'n&apos;est pas un nom de ville valide.';
$MOD_BAKERY['ERR_INVAL_STATE'] = 'n&apos;est pas un nom de r&eacute;gion valide.';
$MOD_BAKERY['ERR_INVAL_COUNTRY'] = 'n&apos;est pas un nom de pays valide.';
$MOD_BAKERY['ERR_INVAL_EMAIL'] = 'n&apos;est pas une adresse email valide.';
$MOD_BAKERY['ERR_INVAL_ZIP'] = 'n&apos;est pas un code postal valide.';
$MOD_BAKERY['ERR_INVAL_PHONE'] = 'n&apos;est pas un num&eacute;ro de t&eacute;l&eacute;phone valide.';
$MOD_BAKERY['ERR_INVAL_TRY_AGAIN'] = 'Veuillez v&eacute;rifier les donn&eacute;es saisie!';
$MOD_BAKERY['ERR_AGREE'] = 'Votre commande ne peut &ecirc;tre valid&eacute;e si vous n&apos;acceptez pas les conditions g&eacute;n&eacute;rales de vente du site.<br />Merci pour votre compr&eacute;hension!';
$MOD_BAKERY['ERR_EMAIL_NOT_SENT'] = 'L&apos;envoi d&apos;email n&apos;a pu &ecirc;tre effectu&eacute;. Votre commande est encore valide. Veuillez contacter l&apos;administrateur de la boutique';

// MODULE BAKERY JAVASCRIPT MESSAGES (Important: Do not remove \n !)
$MOD_BAKERY['TXT_JS_CONFIRM'] = 'Voulez vous vraiment arr\u00EAter votre commande?';
$MOD_BAKERY['TXT_JS_AGREE_TAC'] = 'We can only complete your order if you agree to our terms and conditions.\nThank you for your understanding!';
$MOD_BAKERY['TXT_JS_AGREE_CANCELLATION'] = 'We can only complete your order if you take cognizance of your right of cancellation.\nThank you for your understanding!';
$MOD_BAKERY['TXT_JS_AGREE_PRIVACY'] = 'We can only complete your order if you accept our terms of privady.\nThank you for your understanding!';
$MOD_BAKERY['TXT_JS_BLANK_CAPTCHA'] = 'Veuillez saisir le num\u00E9ro de v\u00E9rification (aussi appel\u00E9 CAPTCHA)!';
$MOD_BAKERY['TXT_JS_INCORRECT_CAPTCHA'] = 'Le num\u00E9ro de v\u00E9rification (aussi appel\u00E9 CAPTCHA) ne correspond pas.\nVeuillez corriger votre saisie!';



// If utf-8 is set as default charset convert some iso-8859-1 strings to utf-8 
if (defined('DEFAULT_CHARSET') && DEFAULT_CHARSET == 'utf-8') {
	$MOD_BAKERY['ADD_REGEXP_CHARS'] = utf8_encode($MOD_BAKERY['ADD_REGEXP_CHARS']);
}

?>