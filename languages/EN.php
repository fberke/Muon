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
$module_description = 'Bakery is a WebsiteBaker shop module with catalog, cart, stock administration, order administration and invoice print feature. Payment in advance, invoice and/or different payment gateways. Further information can be found on the <a href="http://www.bakery-shop.ch" target="_blank">Bakery Website</a>.';

// MODULE BAKERY VARIOUS TEXT
$MOD_BAKERY['TXT_SETTINGS'] = 'Settings';
$MOD_BAKERY['TXT_GENERAL_SETTINGS'] = 'General Settings';
$MOD_BAKERY['TXT_PAGE_SETTINGS'] = 'Page Settings';
$MOD_BAKERY['TXT_PAYMENT_METHODS'] = 'Payment Methods';
$MOD_BAKERY['TXT_SHOP'] = 'Shop';
$MOD_BAKERY['TXT_PAYMENT'] = 'Payment';
$MOD_BAKERY['TXT_EMAIL'] = 'Email';
$MOD_BAKERY['TXT_LAYOUT'] = 'Layout';
$MOD_BAKERY['TXT_PAGE_OFFLINE'] = 'Set Page offline';
$MOD_BAKERY['TXT_OFFLINE_TEXT'] = 'Offline Text';
$MOD_BAKERY['TXT_CONTINUE_URL'] = 'Continue Shopping URL';
$MOD_BAKERY['TXT_OVERVIEW'] = 'Overview';
$MOD_BAKERY['TXT_DETAIL'] = 'Item Detail';
$MOD_BAKERY['TXT_SHOP_NAME'] = 'Shop Name';
$MOD_BAKERY['TXT_TAC_URL'] = 'Terms & Conditions URL';
$MOD_BAKERY['TXT_CANCELLATION_URL'] = 'Cancellation URL';
$MOD_BAKERY['TXT_PRIVACY_URL'] = 'Privacy URL';
$MOD_BAKERY['TXT_SHOP_EMAIL'] = 'Shop Email';
$MOD_BAKERY['TXT_SHOP_COUNTRY'] = 'Shop Country';
$MOD_BAKERY['TXT_SHOP_STATE'] = 'Shop State';
$MOD_BAKERY['TXT_ADDRESS_FORM'] = 'Address Form';
$MOD_BAKERY['TXT_SHIPPING_FORM_REQUEST'] = 'on request';
$MOD_BAKERY['TXT_SHIPPING_FORM_HIDEABLE'] = 'hideable';
$MOD_BAKERY['TXT_SHIPPING_FORM_ALWAYS'] = 'always';
$MOD_BAKERY['TXT_SHOW_STATE_FIELD'] = 'Show State Field';
$MOD_BAKERY['TXT_SHOW_ZIP_END_OF_ADDRESS'] = 'ZIP at End of Address';
$MOD_BAKERY['TXT_ALLOW_OUT_OF_STOCK_ORDERS'] = 'Allow out of Stock Orders';
$MOD_BAKERY['TXT_SKIP_CART_AFTER_ADDING_ITEM'] = 'Skip cart view after adding item to cart';
$MOD_BAKERY['TXT_MINICART_STRONGLY_RECOMMENDED'] = 'MiniCart strongly recommended';
$MOD_BAKERY['TXT_DISPLAY_SETTINGS_TO_ADMIN_ONLY'] = 'Display Settings to Admin (id = 1) only';
$MOD_BAKERY['TXT_FREE_DEFINABLE_FIELD'] = 'Free definable Field';
$MOD_BAKERY['TXT_STOCK_MODE_TEXT'] = 'Show Stock to Customers as Text';
$MOD_BAKERY['TXT_STOCK_MODE_IMAGE'] = 'Show Stock to Customers as Image';
$MOD_BAKERY['TXT_STOCK_MODE_NUMBER'] = 'Show Stock to Customers as Number';
$MOD_BAKERY['TXT_STOCK_MODE_NONE'] = 'Do not show Stock to Customers';
$MOD_BAKERY['TXT_SHOP_CURRENCY'] = 'Shop Currency Code';
$MOD_BAKERY['TXT_SEPARATOR_FOR'] = 'Separator for';
$MOD_BAKERY['TXT_DECIMAL'] = 'Decimal';
$MOD_BAKERY['TXT_GROUP_OF_THOUSANDS'] = 'Group of Thousands';

$MOD_BAKERY['TXT_PAYMENT_METHOD'] = 'Payment Method';
$MOD_BAKERY['TXT_SELECT_PAYMENT_METHODS'] = 'Select Payment Methods';
$MOD_BAKERY['TXT_PAYMENT_METHOD_COD'] = 'Cash on Delivery';
$MOD_BAKERY['TXT_PAYMENT_METHOD_ADVANCE'] = 'Advance Payment';
$MOD_BAKERY['TXT_PAYMENT_METHOD_INVOICE'] = 'Invoice';
$MOD_BAKERY['TXT_PAYMENT_METHOD_PAYMENT_NETWORK'] = 'DIRECTebanking.com';
$MOD_BAKERY['TXT_SKIP_CHECKOUT'] = 'Skip Checkout  if only 1 Payment Method is selected';
$MOD_BAKERY['TXT_NO_PAYMENT_METHOD_SETTING'] = 'No Payment Method Setting to be set.';
$MOD_BAKERY['TXT_NOTICE'] = 'Notice';
$MOD_BAKERY['TXT_DAYS'] = 'Days';

$MOD_BAKERY['TXT_TAX_RATE'] = 'Tax Rate';
$MOD_BAKERY['TXT_SAVED_TAX_RATE'] = 'Currently saved Tax Rate';
$MOD_BAKERY['TXT_SET_TAX_RATE'] = 'Set Tax Rate';
$MOD_BAKERY['TXT_TAX_INCLUDED'] = 'Prices incl. VAT';
$MOD_BAKERY['TXT_TAX_EXCLUDED'] = 'Prices excl. VAT';
$MOD_BAKERY['TXT_TAX_FREE'] = 'Free of VAT';
$MOD_BAKERY['TXT_PLUS_SHIPPING'] = 'plus shipping';
$MOD_BAKERY['TXT_DOMESTIC'] = 'domestic';
$MOD_BAKERY['TXT_ZONE_COUNTRIES'] = 'to specific Countries (Multiple Choice)';
$MOD_BAKERY['TXT_ABROAD'] = 'abroad';
$MOD_BAKERY['TXT_PER_ITEM'] = 'per Product';
$MOD_BAKERY['TXT_SHIPPING_BASED_ON'] = 'Shipping based on';
$MOD_BAKERY['TXT_SHIPPING_METHOD_FLAT'] = 'a flat Amount';
$MOD_BAKERY['TXT_SHIPPING_METHOD_ITEMS'] = 'Number of Items';
$MOD_BAKERY['TXT_SHIPPING_METHOD_POSITIONS'] = 'Number of Positions';
$MOD_BAKERY['TXT_SHIPPING_METHOD_PERCENTAGE'] = 'Percentage of Subtotal';
$MOD_BAKERY['TXT_SHIPPING_METHOD_HIGHEST'] = 'Item with the highest Shipping Cost';
$MOD_BAKERY['TXT_SHIPPING_METHOD_NONE'] = 'none';
$MOD_BAKERY['TXT_FREE_SHIPPING'] = 'Free Shipping';
$MOD_BAKERY['TXT_OVER'] = 'over';
$MOD_BAKERY['TXT_SHOW_FREE_SHIPPING_MSG'] = 'Inform Customers about free Shipping Limit';
$MOD_BAKERY['TXT_EMAIL_SUBJECT'] = 'Email Subject';
$MOD_BAKERY['TXT_EMAIL_BODY'] = 'Email Body';
$MOD_BAKERY['TXT_ITEM'] = 'Product';
$MOD_BAKERY['TXT_ITEMS'] = 'Products';
$MOD_BAKERY['TXT_ITEMS_PER_PAGE'] = 'Products per Page';
$MOD_BAKERY['TXT_NUMBER_OF_COLUMS'] = 'Number of Colums';
$MOD_BAKERY['TXT_USE_CAPTCHA'] = 'Use Captcha';
$MOD_BAKERY['TXT_MODIFY_THIS'] = 'Update Page Settings of <b>current</b> Bakery Page only.';
$MOD_BAKERY['TXT_MODIFY_ALL'] = 'Update Page Settings (without &quot;Continue Shopping URL&quot;) of <b>all</b> Shop Pages.';
$MOD_BAKERY['TXT_MODIFY_MULTIPLE'] = 'Update Page Settings (without &quot;Continue Shopping URL&quot;) of <b>selected</b> Shop Page(s) (Multiple Choice):';

$MOD_BAKERY['PREVIOUS_ITEM'] = '<< Previous Item';
$MOD_BAKERY['ITEM_OVERVIEW'] = 'Item Overview';
$MOD_BAKERY['NEXT_ITEM'] = 'Next Item >>';

$MOD_BAKERY['TXT_ADD_ITEM'] = 'Add Product';
$MOD_BAKERY['TXT_NAME'] = 'Product Name';
$MOD_BAKERY['TXT_SKU'] = 'SKU#';
$MOD_BAKERY['TXT_PRICE'] = 'Price';
$MOD_BAKERY['TXT_OPTION_NAME'] = 'Option Name';
$MOD_BAKERY['TXT_OPTION_ATTRIBUTES'] = 'Option Attributes';
$MOD_BAKERY['TXT_OPTION_PRICE'] = 'Option Price';
$MOD_BAKERY['TXT_ITEM_OPTIONS'] = 'Item Options';
$MOD_BAKERY['TXT_EG_OPTION_NAME'] = 'eg. color';
$MOD_BAKERY['TXT_EG_OPTION_ATTRIBUTE'] = 'eg. red';
$MOD_BAKERY['TXT_INCL'] = 'Inclusive';
$MOD_BAKERY['TXT_EXCL_SHIPPING_TAX'] = 'excluding Shipping and Tax';
$MOD_BAKERY['TXT_TAX'] = 'Tax';
$MOD_BAKERY['TXT_QUANTITY'] = 'Quantity';
$MOD_BAKERY['TXT_SUM'] = 'Sum';
$MOD_BAKERY['TXT_SUBTOTAL'] = 'Subtotal';
$MOD_BAKERY['TXT_TOTAL'] = 'Total';
$MOD_BAKERY['TXT_SHIPPING'] = 'Shipping';
$MOD_BAKERY['TXT_SHIPPING_COST'] = 'Shipping';
$MOD_BAKERY['TXT_DESCRIPTION'] = 'Brief Description';
$MOD_BAKERY['TXT_CHARACTERISTICS'] = 'Main Product Characteristics';
$MOD_BAKERY['TXT_FULL_DESC'] = 'Full Description';
$MOD_BAKERY['TXT_VIEW_ITEM_TEXTAREA'] = 'Notification';
$MOD_BAKERY['TXT_PREVIEW'] = 'Preview';
$MOD_BAKERY['TXT_FILE_NAME'] = 'File Name';
$MOD_BAKERY['TXT_MAIN_IMAGE'] = 'Main Image';
$MOD_BAKERY['TXT_THUMBNAIL'] = 'Thumbnail';
$MOD_BAKERY['TXT_IMAGE'] = 'Image';
$MOD_BAKERY['TXT_IMAGES'] = 'Images';
$MOD_BAKERY['TXT_MAX_WIDTH'] = 'max. Width (px)';
$MOD_BAKERY['TXT_MAX_HEIGHT'] = 'max. Height (px)';
$MOD_BAKERY['TXT_JPG_QUALITY'] = 'JPG Quality';
$MOD_BAKERY['TXT_NON'] = 'non';
$MOD_BAKERY['TXT_ITEM_TO_PAGE'] = 'Move Item to Page';
$MOD_BAKERY['TXT_MOVE'] = 'move';
$MOD_BAKERY['TXT_DUPLICATE'] = 'duplicate';

$MOD_BAKERY['TXT_CART'] = 'Shopping Cart';
$MOD_BAKERY['TXT_ORDER'] = 'Order';
$MOD_BAKERY['TXT_ORDER_ID'] = 'Order#';
$MOD_BAKERY['TXT_CONTINUE_SHOPPING'] = 'Continue shopping';
$MOD_BAKERY['TXT_ADD_TO_CART'] = 'Add to cart';
$MOD_BAKERY['TXT_VIEW_CART'] = 'View cart';
$MOD_BAKERY['TXT_UPDATE_CART'] = 'Update cart';
$MOD_BAKERY['TXT_UPDATE_CART_SUCCESS'] = 'Cart was updated successfully.';
$MOD_BAKERY['TXT_SUBMIT_ORDER'] = 'Submit order';
$MOD_BAKERY['TXT_SUBMIT_BUY'] = 'Submit order';
$MOD_BAKERY['TXT_QUIT_ORDER'] = 'Quit order';
$MOD_BAKERY['TXT_ORDER_SUMMARY'] = 'Order summary';

$MOD_BAKERY['TXT_ADDRESS'] = 'Address';
$MOD_BAKERY['TXT_MODIFY_ADDRESS'] = 'Modify Address';
$MOD_BAKERY['TXT_FILL_IN_ADDRESS'] = 'Please fill in your address';
$MOD_BAKERY['TXT_SHIP_ADDRESS'] = 'Shipping Address';
$MOD_BAKERY['TXT_BACK_TO_CART'] = 'Back to Cart';
$MOD_BAKERY['TXT_ADD_SHIP_FORM'] = 'Add Shipping Address';
$MOD_BAKERY['TXT_HIDE_SHIP_FORM'] = 'Hide Shipping Address';
$MOD_BAKERY['TXT_FILL_IN_SHIP_ADDRESS'] = 'Please fill in the shipping address';
$MOD_BAKERY['TXT_AGREE_TAC'] = 'I agree to the terms and conditions';
$MOD_BAKERY['TXT_AGREE_CANCELLATION'] = 'I took cognizance of my right of cancellation';
$MOD_BAKERY['TXT_AGREE_PRIVACY'] = 'I have read and accepted the terms of privacy';
$MOD_BAKERY['TXT_RIGHT_OF_CANCELLATION'] = 'You have the right to cancel you order within two weeks. For further information please see our terms and conditions.';
$MOD_BAKERY['TXT_CANCEL'] = 'You have canceled your order.';
$MOD_BAKERY['TXT_DELETED'] = 'All your data has been deleted.';
$MOD_BAKERY['TXT_THANK_U_VISIT'] = 'Thank you and have a nice day!';
$MOD_BAKERY['TXT_BACK_TO_SHOP'] = 'Back to Shop';

// MODULE BAKERY CUSTOMER DATA
$MOD_BAKERY['TXT_CUST_EMAIL'] = 'Email Address';
$MOD_BAKERY['TXT_CUST_CONFIRM_EMAIL'] = 'Confirm email';
$MOD_BAKERY['TXT_CUST_FIRST_NAME'] = 'First Name';
$MOD_BAKERY['TXT_CUST_LAST_NAME'] = 'Last Name';
$MOD_BAKERY['TXT_CUST_ADDRESS'] = 'Address';
$MOD_BAKERY['TXT_CUST_CITY'] = 'City';
$MOD_BAKERY['TXT_CUST_STATE'] = 'State';
$MOD_BAKERY['TXT_CUST_COUNTRY'] = 'Country';
$MOD_BAKERY['TXT_CUST_ZIP'] = 'Zip';
$MOD_BAKERY['TXT_CUST_PHONE'] = 'Telephone';

// MODULE BAKERY PROCESS PAYMENT
$MOD_BAKERY['TXT_CHECKOUT'] = 'Checkout';
$MOD_BAKERY['TXT_PAY_METHOD'] = 'Please choose your payment method';
$MOD_BAKERY['TXT_THANK_U_ORDER'] = 'Thank you for your order and have a nice day!';
$MOD_BAKERY['TXT_NO_PAYMENT_METHOD'] = 'No Payment Method activated.';

// MODULE BAKERY ORDER ADMINISTRATION
$MOD_BAKERY['TXT_ORDER_ADMIN'] = 'Order Administration';
$MOD_BAKERY['TXT_ORDER_ARCHIVED'] = 'Archived Orders';
$MOD_BAKERY['TXT_ORDER_CURRENT'] = 'Current Orders';

$MOD_BAKERY['TXT_CUSTOMER'] = 'Customer';
$MOD_BAKERY['TXT_STATUS'] = 'Status';
$MOD_BAKERY['TXT_ORDER_DATE'] = 'Order date';

$MOD_BAKERY['TXT_STATUS_ORDERED'] = 'ordered';
$MOD_BAKERY['TXT_STATUS_SHIPPED'] = 'shipped';
$MOD_BAKERY['TXT_STATUS_BUSY'] = 'Payment in Process';
$MOD_BAKERY['TXT_STATUS_INVOICE'] = 'Invoice';
$MOD_BAKERY['TXT_STATUS_REMINDER'] = 'Reminder';
$MOD_BAKERY['TXT_STATUS_PAID'] = 'paid';
$MOD_BAKERY['TXT_STATUS_ARCHIVE'] = 'archive';
$MOD_BAKERY['TXT_STATUS_ARCHIVED'] = 'archived';

$MOD_BAKERY['TXT_PRINT'] = 'Print';
$MOD_BAKERY['TXT_INVOICE'] = 'Invoice';
$MOD_BAKERY['TXT_DELIVERY_NOTE'] = 'Delivery Note';
$MOD_BAKERY['TXT_REMINDER'] = 'Reminder';
$MOD_BAKERY['TXT_PRINT_INVOICE'] = 'Print invoice';

// MODULE BAKERY STOCK ADMINISTRATION
$MOD_BAKERY['TXT_STOCK_ADMIN'] = 'Stock Administration';
$MOD_BAKERY['TXT_STOCK'] = 'Stock';
$MOD_BAKERY['TXT_IN_STOCK'] = 'in Stock';
$MOD_BAKERY['TXT_SHORT_OF_STOCK'] = 'Short of Stock';
$MOD_BAKERY['TXT_OUT_OF_STOCK'] = 'Out of Stock';
$MOD_BAKERY['TXT_NA'] = 'n/a';
$MOD_BAKERY['TXT_ALL'] = 'all';
$MOD_BAKERY['TXT_ORDER_ASC'] = 'order ascending';
$MOD_BAKERY['TXT_ORDER_DESC'] = 'order descending';
$MOD_BAKERY['TXT_SHORT_OF_STOCK_SUBSEQUENT_DELIVERY'] = 'These items are short of stock.<br />You will get a subsequent delivery';
$MOD_BAKERY['TXT_SHORT_OF_STOCK_QUANTITY_CAPPED'] = 'These items are short of stock - the quantity has been adjusted';
$MOD_BAKERY['TXT_AVAILABLE_QUANTITY'] = 'are available yet';

// EDIT CSS BUTTON
$GLOBALS['TEXT']['CAP_EDIT_CSS'] = 'Edit CSS';

// MODULE BAKERY ERROR MESSAGES (Important: Do not remove <br /> !)
$MOD_BAKERY['ERR_INVALID_FILE_NAME'] = 'Invalide file name';
$MOD_BAKERY['ERR_OFFLINE_TEXT'] = 'This Shop Page is offline for maintenance. Please visite later.<br />Sorry for any inconvenience.';
$MOD_BAKERY['ERR_NO_ORDER_ID'] = 'No SKU number found.';
$MOD_BAKERY['ERR_CART_EMPTY'] = 'The shopping cart is empty.'; 
$MOD_BAKERY['ERR_ITEM_EXISTS'] = 'This product is already added to your cart.<br />You can change the quantity in the shopping cart.';
$MOD_BAKERY['ERR_QUANTITY_ZERO'] = 'The quantity must be a number greater than zero!';
$MOD_BAKERY['ERR_FIELD_BLANK'] = 'The fields highlighted in red are blank. Please enter the required information!';
$MOD_BAKERY['ERR_EMAILS_NOT_MATCHED'] = 'The email addresses did not match!';
$MOD_BAKERY['ERR_INVAL_NAME'] = 'is not a valid name.';
$MOD_BAKERY['ERR_INVAL_STREET'] = 'is not a valid address.';
$MOD_BAKERY['ERR_INVAL_CITY'] = 'is not a valid city.';
$MOD_BAKERY['ERR_INVAL_STATE'] = 'is not a valid state.';
$MOD_BAKERY['ERR_INVAL_COUNTRY'] = 'is not a valid country.';
$MOD_BAKERY['ERR_INVAL_EMAIL'] = 'is not a valid email address.';
$MOD_BAKERY['ERR_INVAL_ZIP'] = 'is not a valid zip.';
$MOD_BAKERY['ERR_INVAL_PHONE'] = 'is not a valid phone number.';
$MOD_BAKERY['ERR_INVAL_TRY_AGAIN'] = 'Please verify your entries!';
$MOD_BAKERY['ERR_AGREE'] = 'We can only complete your order if you agree to our terms and conditions.<br />Thank you for your understanding!';
$MOD_BAKERY['ERR_EMAIL_NOT_SENT'] = 'Unable to send email. Your order is still valide. Please contact the shop admin';

// MODULE BAKERY JAVASCRIPT MESSAGES (Important: Do not remove \n !)
$MOD_BAKERY['TXT_JS_CONFIRM'] = 'Do you really want to quit your order?';
$MOD_BAKERY['TXT_JS_AGREE_TAC'] = 'We can only complete your order if you agree to our terms and conditions.\nThank you for your understanding!';
$MOD_BAKERY['TXT_JS_AGREE_CANCELLATION'] = 'We can only complete your order if you take cognizance of your right of cancellation.\nThank you for your understanding!';
$MOD_BAKERY['TXT_JS_AGREE_PRIVACY'] = 'We can only complete your order if you accept our terms of privady.\nThank you for your understanding!';
$MOD_BAKERY['TXT_JS_BLANK_CAPTCHA'] = 'Please enter the verification number (also known as Captcha)!';
$MOD_BAKERY['TXT_JS_INCORRECT_CAPTCHA'] = 'The verification number (also known as Captcha) does not match.\nPlease correct your entry!';



// If utf-8 is set as default charset convert some iso-8859-1 strings to utf-8 
if (defined('DEFAULT_CHARSET') && DEFAULT_CHARSET == 'utf-8') {
	$MOD_BAKERY['ADD_REGEXP_CHARS'] = utf8_encode($MOD_BAKERY['ADD_REGEXP_CHARS']);
}

?>