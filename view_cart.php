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
 

// Default cart thumb max. size (px)
$cart_thumb_max_size = 40;

// Include WB template parser and create template object
require_once(WB_PATH.'/include/phplib/template.inc');
$tpl = new Template(WB_PATH.'/modules/bakery/templates/cart');
// Define how to deal with unknown {PLACEHOLDERS} (remove:=default, keep, comment)
$tpl->set_unknowns('comment');
// Define debug mode (0:=disabled (default), 1:=variable assignments, 2:=calls to get variable, 4:=debug internals)
$tpl->debug = 0;



// EMPTY CART
// **********

// If cart is empty, show an error message and a "continue shopping" button
$sql_result1 = $database->query("SELECT * FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id = '$order_id'");
$n_row = $sql_result1->numRows();
if ($n_row < 1) {
	// Show empty cart error message using template file
	$tpl->set_file('empty_cart', 'empty.htm');
	$tpl->set_var(array(
		'ERR_CART_EMPTY'		=>	$MOD_BAKERY['ERR_CART_EMPTY'],
		'TXT_CONTINUE_SHOPPING'		=>	$MOD_BAKERY['TXT_CONTINUE_SHOPPING']
	));
	$tpl->pparse('output', 'empty_cart');
	return;
}



// GET ITEM DETAILS FROM DATABASE
// ******************************

// Get order id, item id, attributes, sku, quantity, price and tax_rate from db table order
$i = 1;
while ($row1 = $sql_result1->fetchRow()) {
	foreach ($row1 as $field => $value) {
		if ($field != "order_id") {
			$items[$i][$field] = $value;
			// Get item name, shipping. link and main image from db items table
			if ($field == "item_id") {
				$sql_result2 = $database->query("SELECT title, shipping, link, main_image FROM " .TABLE_PREFIX."mod_bakery_items WHERE item_id = '".$row1['item_id']."'");
				$row2 = $sql_result2->fetchRow();	
				$items[$i]['name']       = $row2[0];
				$items[$i]['shipping']   = $row2[1];
				$items[$i]['link']       = WB_URL.PAGES_DIRECTORY.$row2[2].PAGE_EXTENSION;
				$items[$i]['main_image'] = $row2[3];

				// Item thumbnail
				// Default if no thumb exists
				$items[$i]['thumb_url']    = WB_PATH.'/modules/bakery/images/transparent.gif';
				$items[$i]['thumb_width']  = $cart_thumb_max_size;
				$items[$i]['thumb_height'] = $cart_thumb_max_size;
				
				// Item thumb if exists
				$thumb_dir               = '/bakery/thumbs/item'.$row1['item_id'].'/';
				$items[$i]['thumb_path'] = WB_PATH.MEDIA_DIRECTORY.$thumb_dir.$items[$i]['main_image'];
				
				if (is_file($items[$i]['thumb_path'])) {
					// Thumb URL
					$items[$i]['thumb_url'] = WB_URL.MEDIA_DIRECTORY.$thumb_dir.$items[$i]['main_image'];
					// Get thumb image size
					$size = getimagesize($items[$i]['thumb_path']);
					if ($size[0] > 1 && $size[1] > 1) {
						if ($size[0] > $size[1]) {
							$items[$i]['thumb_height'] = round($cart_thumb_max_size * $size[1] / $size[0]);
						}
						elseif ($size[0] < $size[1]) {
							$items[$i]['thumb_width']  = round($cart_thumb_max_size * $size[0] / $size[1]);
						}
					}
				}
			} 
		}
	}

	// Default if item has no attributes
	$items[$i]['show_attribute'] = "";
	$items[$i]['attribute_price'] = 0;
	// Get item attribute ids
	if ($items[$i]['attributes'] != "none") {
		$attribute_ids = explode(",", $items[$i]['attributes']);
		foreach ($attribute_ids as $attribute_id) {
			// Get option name and attribute name, price, operator (+/-/=)
			$query_attributes = $database->query("SELECT o.option_name, a.attribute_name, ia.price, ia.operator FROM ".TABLE_PREFIX."mod_bakery_options o INNER JOIN ".TABLE_PREFIX."mod_bakery_attributes a ON o.option_id = a.option_id INNER JOIN ".TABLE_PREFIX."mod_bakery_item_attributes ia ON a.attribute_id = ia.attribute_id WHERE ia.item_id = {$items[$i]['item_id']} AND ia.attribute_id = $attribute_id");
			$attribute = $query_attributes->fetchRow();
			// Calculate the item attribute prices sum depending on the operator
			if ($attribute['operator'] == "+") {
				$items[$i]['attribute_price'] = $items[$i]['attribute_price'] + $attribute['price'];
			} elseif ($attribute['operator'] == "-") {
				$items[$i]['attribute_price'] = $items[$i]['attribute_price'] - $attribute['price'];
			// If operator is '=' then override the item price by the attribute price
			} elseif ($attribute['operator'] == "=") {
				$items[$i]['price'] = $attribute['price'];
			}
			// Prepare option and attributes for display in cart table
			$items[$i]['show_attribute'] .= "<br />".$attribute['option_name'].":&nbsp;".$attribute['attribute_name'];
		}
		// Now calculate item price including all attribute prices
		$items[$i]['price'] = $items[$i]['price'] + $items[$i]['attribute_price'];
		// Remove leading comma and space
		//$items[$i]['show_attribute'] = substr($items[$i]['show_attribute'], 2);
		//$items[$i]['show_attribute'] = '<br />'.$items[$i]['show_attribute'];
	}
	
	// textarea
	$items[$i]['show_textarea'] = "";
	if ($items[$i]['textarea'] != "") {
		$items[$i]['show_textarea'] = '<br />'.$MOD_BAKERY['TXT_VIEW_ITEM_TEXTAREA'].': '.$items[$i]['textarea'];
	}
	
	// Increment counter
	$i++;
}



// SHOW TITLE AND MESSAGES IF ANY
// ******************************

// Assign page filename and pagetitle for web analytics
global $bakery_analytics;
$bakery_analytics ['filename'] = str_replace(PAGE_EXTENSION,"",$setting_continue_url).$MOD_BAKERY_FILENAME ['shopping_cart'];
$bakery_analytics ['pagetitle'] = $MOD_BAKERY_PAGETITLE ['shopping_cart'];

// Show cart title using template file
$tpl->set_file('cart_title', 'title.htm');
$tpl->set_var(array(
	'TXT_CART'			=>	$MOD_BAKERY['TXT_CART']
));
$tpl->pparse('output', 'cart_title');

// If enabled show cart success message using template file
if (isset($cart_success)) {
	$tpl->set_file('cart_success', 'success.htm');
	$tpl->set_var(array(
		'TXT_UPDATE_CART_SUCCESS'		=>	$MOD_BAKERY['TXT_UPDATE_CART_SUCCESS']
	));
	$tpl->pparse('output', 'cart_success');
}

// Compose the cart error messages
if (isset($cart_error) && is_array($cart_error)) {
	$message = '';
	foreach ($cart_error as $value) {
		$message .= "<p>".$value."</p>";
	}
	// Show cart error messages using template file
	$tpl->set_file('cart_error', 'error.htm');
	$tpl->set_var(array(
		'MESSAGE'					=>	$message,
		'TXT_CONTINUE_SHOPPING'		=>	$MOD_BAKERY['TXT_CONTINUE_SHOPPING']
	));
	$tpl->pparse('output', 'cart_error');
}



// SHOW CART TABLE
// ***************

// Determine shipping per item sum of all items specified
for ($i = 1; $i <= sizeof($items); $i++) {
	$shipping_array[] = $items[$i]['shipping'];
}

// Determine shipping sum of all items specified
for ($i = 1; $i <= sizeof($items); $i++) {
	$shipping_sum[] = $items[$i]['shipping'];
}
$shipping_sum = array_sum($shipping_sum);
/*
// With shipping per item 
if ($shipping_sum > 0) {
	$display_shipping = "";
	$colspan_l = 7;
	$colspan_m = 6;
}
// No shipping per item
else {
	$display_shipping = "none";
	$colspan_l = 6;
	$colspan_m = 5;
}
*/
// Never show shipping column in this version -- fberke
	$display_shipping = "none";
	$colspan_l = 6;
	$colspan_m = 5;

// Show cart table header using template file
$tpl->set_file('cart_table_header', 'table_header.htm');
$tpl->set_var(array(
	'TXT_ORDER_ID'		=>	$MOD_BAKERY['TXT_ORDER_ID'],
	'ORDER_ID'		=>	$order_id,
	'SETTING_CONTINUE_URL'	=>	$setting_continue_url,
	'TXT_SKU'		=>	$MOD_BAKERY['TXT_SKU'],
	'TXT_NAME'		=>	$MOD_BAKERY['TXT_NAME'],
	'TXT_QUANTITY'		=>	$MOD_BAKERY['TXT_QUANTITY'],
	'TXT_PRICE'		=>	$MOD_BAKERY['TXT_PRICE'],
	'SETTING_SHOP_CURRENCY'	=>	$setting_shop_currency,
	'DISPLAY_SHIPPING'	=>	$display_shipping,
	'TXT_SHIPPING'		=>	$MOD_BAKERY['TXT_SHIPPING'],
	'TXT_SUM'		=>	$MOD_BAKERY['TXT_SUM'],
	'COLSPAN_L'		=>	$colspan_l
));
$tpl->pparse('output', 'cart_table_header');


// Loop through items
$order_total = 0;
$order_subtotal = 0;
$count_items = 0;
$item_shipping_subtotal = 0;
// We don't need sales tax in Cart, only in summary
//$sales_tax = 0;

for ($i = 1; $i <= sizeof($items); $i++) {

//	// Calculate order total with shipping per item
//	if ($shipping_sum > 0) {
//		$f_price = number_format($items[$i]['price'], 2, $setting_dec_point, $setting_thousands_sep);
//		$f_shipping = number_format($items[$i]['shipping'], 2, $setting_dec_point, $setting_thousands_sep);
//		// See http://www.bakery-shop.ch/#shipping_total
//		// $total = $items[$i]['quantity'] * ($items[$i]['price'] + $items[$i]['shipping']);
//		$total = $items[$i]['quantity'] * $items[$i]['price'];
//		$f_total = number_format($total, 2, $setting_dec_point, $setting_thousands_sep);
//		$order_total = $order_total + $total;
//		$f_order_total = number_format($order_total, 2, $setting_dec_point, $setting_thousands_sep);
//	}
	// Calculate order total without shipping per item
//	else {
//		$f_price = number_format($items[$i]['price'], 2, $setting_dec_point, $setting_thousands_sep);
//		$f_shipping = 0;
//		$total = $items[$i]['quantity'] * $items[$i]['price'];
//		$f_total = number_format($total, 2, $setting_dec_point, $setting_thousands_sep);
//		$order_total = $order_total + $total;
//		$f_order_total = number_format($order_total, 2, $setting_dec_point, $setting_thousands_sep);
//	}

	// Calculate order subtotal and shipping per item subtotal (w/o tax and general shipping)
	if ($shipping_sum > 0) {
		$f_price = number_format($items[$i]['price'], 2, $setting_dec_point, $setting_thousands_sep);
		$f_shipping = number_format($items[$i]['shipping'], 2, $setting_dec_point, $setting_thousands_sep);
  		// See http://www.bakery-shop.ch/#shipping_total
		// $item_total = $items[$i]['quantity'] * ($items[$i]['price'] + $items[$i]['shipping']);
		$item_total = $items[$i]['quantity'] * $items[$i]['price'];
		$item_shipping = $items[$i]['quantity'] * $items[$i]['shipping'];
		$f_total = number_format($item_total, 2, $setting_dec_point, $setting_thousands_sep);
		$order_subtotal = $order_subtotal + $item_total;
		$item_shipping_subtotal = $item_shipping_subtotal + $item_shipping;
		$f_order_subtotal = number_format($order_subtotal, 2, $setting_dec_point, $setting_thousands_sep);
	}
	// Calculate order subtotal without shipping per item (w/o tax and general shipping)
	else {
		$f_price = number_format($items[$i]['price'], 2, $setting_dec_point, $setting_thousands_sep);
		$f_shipping = 0;
		$item_total = $items[$i]['quantity'] * $items[$i]['price'];
		$f_total = number_format($item_total, 2, $setting_dec_point, $setting_thousands_sep);
		$order_subtotal = $order_subtotal + $item_total;
		$f_order_subtotal = number_format($order_subtotal, 2, $setting_dec_point, $setting_thousands_sep);
	}

	// Count number of items for shipping per item
	$count_items += $items[$i]['quantity'];

	// Show cart table body using template file 
	$tpl->set_file('cart_table_body', 'table_body.htm');
	$tpl->set_var(array(
		'THUMB_URL'		=>	$items[$i]['thumb_url'],
		'THUMB_WIDTH'		=>	$items[$i]['thumb_width'],
		'THUMB_HEIGHT'		=>	$items[$i]['thumb_height'],
		'LINK'			=>	$items[$i]['link'],
		'SKU'			=>	$items[$i]['sku'],
		'NAME'			=>	$items[$i]['name'],
		'ATTRIBUTE'		=>	$items[$i]['show_attribute'],
		'TEXTAREA' 		=> 	$items[$i]['show_textarea'],
		'ITEM_ID'		=>	$items[$i]['item_id'],
		'ATTRIBUTES'		=>	$items[$i]['attributes'],
		'QUANTITY'		=>	$items[$i]['quantity'],
		'WB_URL'		=>	WB_URL,
		'TEXT_DELETE'		=>	$TEXT['DELETE'],
		'PRICE'			=>	$f_price,
		'PRICE_RAW'		=>	$items[$i]['price'],
		'DISPLAY_SHIPPING'	=>	$display_shipping,
		'SHIPPING'		=>	$f_shipping,
		'TOTAL'			=>	$f_total
	));
	$tpl->pparse('output', 'cart_table_body');
}

// CALCULATE TOTAL	
// ***************

// Assume domestic shipping for cart
$effective_country = $setting_shop_country;

// Select shipping rate
if ($effective_country == $setting_shop_country) {
   $setting_shipping_rate = $setting_shipping_domestic;
}
elseif (in_array($effective_country, $setting_zone_countries)) {
	$setting_shipping_rate = $setting_shipping_zone;
}
else {
	$setting_shipping_rate = $setting_shipping_abroad;
}

// Calculate shipping
if ($setting_shipping_method == "highest") {
	// Determine highest shipping per item of all items specified
	$highest_shipping = $shipping_array;
	rsort($highest_shipping, SORT_NUMERIC);
	$shipping = $highest_shipping[0];
} else {
	// Determine shipping and add shipping per item subtotal
	if ($setting_shipping_method == "flat") {
		$shipping = $setting_shipping_rate;
	}
	elseif ($setting_shipping_method == "items") {
		$shipping = $setting_shipping_rate * $count_items;
	}
	elseif ($setting_shipping_method == "positions") {
		$shipping = $setting_shipping_rate * sizeof($items);
	}
	elseif ($setting_shipping_method == "percentage") {
		$shipping = $order_subtotal / 100 * $setting_shipping_rate;
	}
	else {
		$shipping = 0;
	}
	$shipping = $shipping + $item_shipping_subtotal;  // See http://www.bakery-shop.ch/#shipping_total
}

// Text normal shipping
$txt_shipping_cost = $MOD_BAKERY['TXT_SHIPPING_COST'];
$css_class_cart_shipping_f = "mod_bakery_cart_shipping_f";
$css_class_invoice_shipping_b = "mod_bakery_invoice_shipping_b";

// Free shipping for larger amounts
if ($order_subtotal >= $setting_free_shipping) {
	$shipping = 0;
	// Text free shipping
	$txt_shipping_cost = $MOD_BAKERY['TXT_FREE_SHIPPING'];
	$css_class_cart_shipping_f = "mod_bakery_cart_free_shipping_f";
	$css_class_invoice_shipping_b = "mod_bakery_invoice_free_shipping_b";
} else {
	// If no free shipping and shipping tax rate is not 0, add shipping tax rate to the tax rates array
	if ($setting_tax_rate_shipping != 0) {
		$f_tax_rate_array[] = number_format($setting_tax_rate_shipping, 1);	
	}
}

// Convert tax rate array to string for displaying
$f_tax_rate = "";
if (($setting_tax_by == 'country' && $cust_country == $setting_shop_country) || ($setting_tax_by == 'state' && $cust_state_code == $setting_shop_state) && $setting_tax_by != 'none') {
	if (count($f_tax_rate_array) > 0) {
		$f_tax_rate_array = array_unique($f_tax_rate_array);
		$f_tax_rate = implode("/", $f_tax_rate_array)."%";
	}
}

// Format shipping for display
$f_shipping = number_format($shipping, 2, $setting_dec_point, $setting_thousands_sep);


// Shipping sales tax abroad
$sales_tax_shipping = 0;

// Calculate shipping sales tax
if (($setting_tax_by == 'country' && $cust_country == $setting_shop_country) || ($setting_tax_by == 'state' && $cust_state_code == $setting_shop_state) && $setting_tax_by != 'none') {
	// Calculate tax amount for shipping including tax (brutto)
	if ($setting_tax_included == "included") {
		$sales_tax_shipping = $shipping * $setting_tax_rate_shipping / (100 + $setting_tax_rate_shipping);
	}
	else {
		// Calculate tax amount for shipping excluding tax (netto)
		$sales_tax_shipping = $shipping / 100 * $setting_tax_rate_shipping;
	}
}
/*
NOT needed in cart
// Total item and shipping sales tax
$sales_tax = $sales_tax + $sales_tax_shipping;
// Format sales tax for display
$f_sales_tax = number_format($sales_tax, 2, $setting_dec_point, $setting_thousands_sep);
*/

// Calculate total
if ($setting_tax_included == "included") {
	$order_total = $order_subtotal + $shipping;
}
else {
	$order_total = $order_subtotal + /*$sales_tax +*/ $shipping;
	$MOD_BAKERY['TXT_INCL'] = "";
}
$f_order_total = number_format($order_total, 2, $setting_dec_point, $setting_thousands_sep);

// Show order total and buttons using template file
//$tpl->set_file('cart_table_footer', 'table_footer.htm');
//$tpl->set_var(array(
//	'COLSPAN_L'				=>	$colspan_l,
//	'COLSPAN_M'				=>	$colspan_m,
//	'TXT_SUM'				=>	$MOD_BAKERY['TXT_SUM'],
//	'SETTING_SHOP_CURRENCY'	=>	$setting_shop_currency,
//	'TXT_SHIPPING_COST'		=>	$MOD_BAKERY['TXT_SHIPPING_COST'],
//	'ORDER_TOTAL'			=>	$f_order_total,
//	'TXT_CONTINUE_SHOPPING'	=>	$MOD_BAKERY['TXT_CONTINUE_SHOPPING'],
//	'TXT_UPDATE_CART'		=>	$MOD_BAKERY['TXT_UPDATE_CART'],
//	'TXT_SUBMIT_ORDER'		=>	$MOD_BAKERY['TXT_SUBMIT_ORDER'],
//	'ORDER_ID'		    	=>	$order_id
//));
//
//$tpl->pparse('output', 'cart_table_footer');

// Make table of sales tax, shipping, order total and buttons for screen output using template file
$tpl->set_file('cart_table_footer', 'table_footer.htm');
$tpl->set_var(array(
	'COLSPAN_L'			=>	$colspan_l,
	'COLSPAN_M'			=>	$colspan_m,
	'TXT_SUBTOTAL'			=>	$MOD_BAKERY['TXT_SUBTOTAL'],
	'SETTING_SHOP_CURRENCY'		=>	$setting_shop_currency,
	'ORDER_SUBTOTAL'		=>	$f_order_subtotal,
	'ORDER_SUBTOTAL_RAW'		=>	$order_subtotal,
	'CSS_CLASS_CART_SHIPPING'	=>	$css_class_cart_shipping_f,
	'TXT_SHIPPING_COST'		=>	$MOD_BAKERY['TXT_SHIPPING_COST'],
	'SHIPPING'			=>	$f_shipping,
	'SHIPPING_RAW'			=>	$shipping,
/*	'DISPLAY_TAX'			=>	$display_tax,
	'TXT_INCL'			=>	$MOD_BAKERY['TXT_INCL'],
	'TAX_RATE'			=>	$f_tax_rate,
	'TXT_TAX'			=>	$MOD_BAKERY['TXT_TAX'],
	'SALES_TAX'			=>	$f_sales_tax,*/
	'TXT_TOTAL'			=>	$MOD_BAKERY['TXT_TOTAL'],
	'ORDER_TOTAL'			=>	$f_order_total,
	'ORDER_TOTAL_RAW'		=>	$order_total,
	'TXT_CONTINUE_SHOPPING'		=>	$MOD_BAKERY['TXT_CONTINUE_SHOPPING'],
	'TXT_UPDATE_CART'		=>	$MOD_BAKERY['TXT_UPDATE_CART'],
	'TXT_SUBMIT_ORDER'		=>	$MOD_BAKERY['TXT_SUBMIT_ORDER'],
	'ORDER_ID'		    	=>	$order_id,
	'ANALYTICS_FILENAME'	=>	$bakery_analytics ['filename'],
	'ANALYTICS_PAGETITLE'	=>	$bakery_analytics ['pagetitle']
));
$tpl->pparse('output', 'cart_table_footer');

?>