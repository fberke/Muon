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
 

// Include WB template parser and create template object
require_once(WB_PATH.'/include/phplib/template.inc');
$tpl = new Template(WB_PATH.'/modules/bakery/templates/mini_cart');
// Define how to deal with unknown {PLACEHOLDERS} (remove:=default, keep, comment)
$tpl->set_unknowns('keep');
// Define debug mode (0:=disabled (default), 1:=variable assignments, 2:=calls to get variable, 4:=debug internals)
$tpl->debug = 0;

// Look for language file
if (LANGUAGE_LOADED && !isset($MOD_BAKERY)) {
	include(WB_PATH.'/modules/bakery/languages/EN.php');
	if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
		include(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
	}
}


// Check order id
if (isset($_SESSION['bakery']['order_id']) && is_numeric($_SESSION['bakery']['order_id']) && $_SESSION['bakery']['order_id'] >= 0) {
	$order_id = $_SESSION['bakery']['order_id'];

	// Look for items in the db
	$query_order = $database->query("SELECT item_id, attributes, quantity, price FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id = '$order_id'");
	$num_orders = $query_order->numRows();
	if ($num_orders > 0) {

		// Get the section id - if existing - of the last visited Bakery section...
		if (isset($_SESSION['bakery']['last_section_id']) && is_numeric($_SESSION['bakery']['last_section_id'])) {
			$section_id = $_SESSION['bakery']['last_section_id'];
			$clause = "WHERE ps.section_id = '$section_id'";
		}
		// ...else get the highest section id
		else {
			$clause = "WHERE ps.section_id != '0' ORDER BY ps.section_id ASC LIMIT 1";
		}

		// Get continue url
		$query_continue_url = $database->query("SELECT p.link FROM ".TABLE_PREFIX."pages p INNER JOIN ".TABLE_PREFIX."mod_bakery_page_settings ps ON p.page_id = ps.page_id $clause");
		if ($query_continue_url->numRows() > 0) {
			$fetch_continue_url = $query_continue_url->fetchRow();
			$continue_url = WB_URL.PAGES_DIRECTORY.stripslashes($fetch_continue_url['link']).PAGE_EXTENSION;
		}

		// Get the general settings
		$query_general_settings = $database->query("SELECT shop_currency, dec_point, thousands_sep FROM ".TABLE_PREFIX."mod_bakery_general_settings");
		if ($query_general_settings->numRows() > 0) {
			$general_settings = $query_general_settings->fetchRow();
			$shop_currency = stripslashes($general_settings['shop_currency']);
			$dec_point = stripslashes($general_settings['dec_point']);
			$thousands_sep = stripslashes($general_settings['thousands_sep']);
		}

		// Get item_id, attributes, quantity and price from db order table
		$i = 1;
		while ($order = $query_order->fetchRow()) {
			foreach ($order as $key => $value) {
				$items[$i][$key] = $value;
			}

			// Initialize var and set default if item has no attributes
			$attribute['operator'] = "";
			$items[$i]['attribute_price'] = 0;
			// Get item attribute price and operator (+/-)
			if ($items[$i]['attributes'] != "none") {
				$attribute_ids = explode(",", $items[$i]['attributes']);
				foreach ($attribute_ids as $attribute_id) {
					// Get attribute price and operator (+/-)
					$query_attributes = $database->query("SELECT price, operator FROM ".TABLE_PREFIX."mod_bakery_item_attributes WHERE item_id = {$items[$i]['item_id']} AND attribute_id = $attribute_id");
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
				}
				// Now calculate item price including all attribute prices
				$items[$i]['price'] = $items[$i]['price'] + $items[$i]['attribute_price'];
			}
			// Increment counter
			$i++;
		}

		// Calculate order total
		$quantity_sum = 0;
		$total = 0;
		for ($i = 1; $i <= sizeof($items); $i++) {
			$quantity_sum = $quantity_sum + $items[$i]['quantity'];
			$subtotal = $items[$i]['quantity'] * $items[$i]['price'];
			$total = $total + $subtotal;
		}
		$f_total = number_format($total, 2, $dec_point, $thousands_sep);		

		// Show MiniCart summary using template file
		$tpl->set_file('mini_cart_summary', 'summary.htm');

		$tpl->set_var(array(
			'WB_URL'			=>	WB_URL,
			'TXT_CART'			=>	$MOD_BAKERY['TXT_CART'],
			'CONTINUE_URL'		=>	$continue_url,
			'TXT_ORDER_ID'		=>	$MOD_BAKERY['TXT_ORDER_ID'],
			'ORDER_ID' 			=>	$order_id,
			'TXT_ITEMS'			=>	$MOD_BAKERY['TXT_ITEMS'],
			'QUANTITY_SUM' 		=>	$quantity_sum,
			'SHOP_CURRENCY' 	=>	$shop_currency,
			'TOTAL' 			=>	$f_total,
			'TXT_SUM'			=>	$MOD_BAKERY['TXT_SUM'],
			'TXT_EXCL_SHIPPING'	=>	$MOD_BAKERY['TXT_EXCL_SHIPPING_TAX'],
			'TXT_VIEW_CART' 	=>	$MOD_BAKERY['TXT_VIEW_CART']
		));

		$tpl->pparse('output', 'mini_cart_summary');
	}

	else {
		// Show empty MiniCart using template file
		$tpl->set_file('mini_cart_empty', 'empty.htm');

		$tpl->set_var(array(
			'WB_URL'			=>	WB_URL,
			'TXT_CART'			=>	$MOD_BAKERY['TXT_CART'],
			'ERR_CART_EMPTY'	=>	$MOD_BAKERY['ERR_CART_EMPTY']
		));

		$tpl->pparse('output', 'mini_cart_empty');
	}
}

else {
	// Show empty MiniCart using template file
	$tpl->set_file('mini_cart_empty', 'empty.htm');

	$tpl->set_var(array(
		'WB_URL'			=>	WB_URL,
		'TXT_CART'			=>	$MOD_BAKERY['TXT_CART'],
		'ERR_CART_EMPTY'	=>	$MOD_BAKERY['ERR_CART_EMPTY']
	));

	$tpl->pparse('output', 'mini_cart_empty');
}
?>