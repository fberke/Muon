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
 

// Check if there is a start point defined
if (isset($_GET['p']) AND is_numeric($_GET['p']) AND $_GET['p'] >= 0) {
	$position = $_GET['p'];
} else {
	$position = 0;
}

// Get user's username, display name, email, and id - needed for insertion into item info
$users = array();
$query_users = $database->query("SELECT user_id,username,display_name,email FROM ".TABLE_PREFIX."users");
if ($query_users->numRows() > 0) {
	while ($user = $query_users->fetchRow()) {
		// Insert user info into users array
		$user_id = $user['user_id'];
		$users[$user_id]['username'] = $user['username'];
		$users[$user_id]['display_name'] = $user['display_name'];
		$users[$user_id]['email'] = $user['email'];
	}
}

// Update the section id of the last visited Bakery section for use with MiniCart
$_SESSION['bakery']['last_section_id'] = $section_id;

// Get general settings
$query_general_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_general_settings");
if ($query_general_settings->numRows() > 0) {
	$fetch_general_settings = $query_general_settings->fetchRow();
	
	$setting_shop_name = stripslashes($fetch_general_settings['shop_name']);
	$setting_shop_email = stripslashes($fetch_general_settings['shop_email']);
	$setting_tac_url = stripslashes($fetch_general_settings['tac_url']);
	$setting_cancellation_url = stripslashes($fetch_general_settings['cancellation_url']);
	$setting_privacy_url = stripslashes($fetch_general_settings['privacy_url']);
	$setting_shop_country = stripslashes($fetch_general_settings['shop_country']);
	$setting_shop_state = stripslashes($fetch_general_settings['shop_state']);
	$setting_shipping_form = stripslashes($fetch_general_settings['shipping_form']);
	$setting_state_field = stripslashes($fetch_general_settings['state_field']);
	$setting_zip_location = stripslashes($fetch_general_settings['zip_location']);
	$setting_skip_cart = stripslashes($fetch_general_settings['skip_cart']);
	$setting_use_captcha = stripslashes($fetch_general_settings['use_captcha']);
	
	$setting_definable_field_0 = stripslashes($fetch_general_settings['definable_field_0']);
	$setting_definable_field_1 = stripslashes($fetch_general_settings['definable_field_1']);
	$setting_definable_field_2 = stripslashes($fetch_general_settings['definable_field_2']);
	$setting_stock_mode = stripslashes($fetch_general_settings['stock_mode']);
	$setting_stock_limit = stripslashes($fetch_general_settings['stock_limit']);
	$setting_out_of_stock_orders = stripslashes($fetch_general_settings['out_of_stock_orders']);

	$setting_shop_currency = stripslashes($fetch_general_settings['shop_currency']);
	// todo: enhance list to cover most common countries and their currencies
	if ($setting_shop_currency == "EUR") {
		$setting_shop_currency = "€";
	} elseif ($setting_shop_currency == "GBP") {
		$setting_shop_currency == "£";
	} elseif ($setting_shop_currency == "USD") {
		$setting_shop_currency == "$";
	}
	$setting_dec_point = stripslashes($fetch_general_settings['dec_point']);
	$setting_thousands_sep = stripslashes($fetch_general_settings['thousands_sep']);
	$setting_tax_by = stripslashes($fetch_general_settings['tax_by']);
	$setting_tax_rate = stripslashes($fetch_general_settings['tax_rate']);
	$setting_tax_rate1 = stripslashes($fetch_general_settings['tax_rate1']);
	$setting_tax_rate2 = stripslashes($fetch_general_settings['tax_rate2']);
	$setting_tax_included = stripslashes($fetch_general_settings['tax_included']);
	$setting_skip_checkout = stripslashes($fetch_general_settings['skip_checkout']);
	
	$setting_tax_rate_shipping = stripslashes($fetch_general_settings['tax_rate_shipping']);
	$setting_free_shipping = stripslashes($fetch_general_settings['free_shipping']);
	$setting_free_shipping_msg = stripslashes($fetch_general_settings['free_shipping_msg']);
	$setting_shipping_method = stripslashes($fetch_general_settings['shipping_method']);
	$setting_shipping_domestic = stripslashes($fetch_general_settings['shipping_domestic']);
	$setting_shipping_abroad = stripslashes($fetch_general_settings['shipping_abroad']);
	$setting_shipping_zone = stripslashes($fetch_general_settings['shipping_zone']);
	$setting_zone_countries = explode(",", stripslashes($fetch_general_settings['zone_countries']));  // make array
	$setting_shipping_d_a = $setting_shipping_domestic."/".$setting_shipping_abroad;
}


// Look for language file
if (LANGUAGE_LOADED) {
	if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
		include(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
	} else {
		include(WB_PATH.'/modules/bakery/languages/EN.php');
	}
}


// determine which tax info to display
if ($setting_tax_by == "none") {
	$tax_info = $MOD_BAKERY['TXT_TAX_FREE'];
} else {
	$tax_info = ($setting_tax_included == "included") ? $MOD_BAKERY['TXT_TAX_INCLUDED'] : $MOD_BAKERY['TXT_TAX_EXCLUDED'];
}
	
// Get payment method settings
$query_payment_methods = $database->query("SELECT directory FROM ".TABLE_PREFIX."mod_bakery_payment_methods WHERE active = '1'");
if ($query_payment_methods->numRows() > 0) {
	while ($fetch_payment_methods = $query_payment_methods->fetchRow()) {
		$setting_payment_methods[] = stripslashes($fetch_payment_methods['directory']);
	}
} else {
	$setting_payment_methods = array();
}
$num_payment_methods = count($setting_payment_methods);
$skip_checkout = ($setting_skip_checkout == 1 && $num_payment_methods == 1) ? true : false;
// If checkout is omitted (1 step less) switch the directory for the step 1-2-3 images to step 1-2 images
$step_img_dir = $skip_checkout ? "2_steps" : "3_steps";

// Get page settings
$query_page_settings = $database->query("SELECT page_offline, offline_text FROM ".TABLE_PREFIX."mod_bakery_page_settings WHERE section_id = '$section_id'");
if ($query_page_settings->numRows() > 0) {
	$fetch_page_settings = $query_page_settings->fetchRow();
	
	$setting_page_offline = stripslashes($fetch_page_settings['page_offline']);
	$setting_offline_text = stripslashes($fetch_page_settings['offline_text']);
}

// Get continue url
$query_continue_url = $database->query("SELECT p.link FROM ".TABLE_PREFIX."pages p INNER JOIN ".TABLE_PREFIX."mod_bakery_page_settings ps ON p.page_id = ps.page_id WHERE p.page_id = ps.continue_url AND ps.section_id = '$section_id'");
if ($query_continue_url->numRows() > 0) {
	$fetch_continue_url = $query_continue_url->fetchRow();
	$setting_continue_url = WB_URL.PAGES_DIRECTORY.stripslashes($fetch_continue_url['link']).PAGE_EXTENSION;
}

// Add a wrapper for Bakery to help with layout
echo "\n<div id='mod_bakery_wrapper_f'>\n";
$end_of_wrapper = "\n</div> <!-- End of bakery wrapper -->\n";








// ***************************************************************************************** //
// Check if we should show the SHOPPING CART, PROCESS ORDER, the MAIN PAGE or an ITEM itself //
// ***************************************************************************************** //


// GENERATE ORDER ID FOR NEW ORDERS
// ********************************

// MSIE image buttons only submit the click coordinates like 'anything_x' and 'anything_y'
// Convert POST name 'anything_x' to 'anything'
if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
	foreach ($_POST as $key => $value) {
		$count = 0;
		$ie_post_key = str_replace('_x', '', $key, $count);
		if ($count > 0) {
			$_POST[$ie_post_key] = 1;
		}
	}
}

// Check submitted POST/GET vars
if (isset($_REQUEST['view_cart'])  && ($_REQUEST['view_cart'] != "") || // normally POST, GET for MiniCart
   isset($_POST['add_to_cart'])    && ($_POST['add_to_cart'] != "") ||
   isset($_POST['update_cart'])    && ($_POST['update_cart'] != "") ||
   isset($_POST['submit_order'])   && ($_POST['submit_order'] != "") ||
   isset($_POST['hide_ship_form']) && ($_POST['hide_ship_form'] != "") ||
   isset($_POST['add_ship_form'])  && ($_POST['add_ship_form'] != "")) {

	// Check order id
	if (!isset($_SESSION['bakery']['order_id']) || ($_SESSION['bakery']['order_id'] == "")) {
		//$mktime = @mktime();
		$mktime = @time();
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_customer (order_date) VALUES ('$mktime')");
		$order_id = mysql_insert_id(); 
		$_SESSION['bakery']['order_id'] = $order_id;
		
		// Delete db records of not submitted orders older than 1 hour
		$outdate = $mktime - (60 * 60 * 1);
		$query_outdated_orders = $database->query("SELECT order_id FROM " .TABLE_PREFIX."mod_bakery_customer WHERE order_date < $outdate AND submitted = 'no'");
		if ($query_outdated_orders->numRows() > 0) {
			while ($outdated_orders = $query_outdated_orders->fetchRow()) {
				$outdated_order_id = stripslashes($outdated_orders['order_id']);

				// First put not sold items back to stock...
				$query_order = $database->query("SELECT item_id, quantity FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id = '$outdated_order_id'");
				if ($query_order->numRows() > 0) {
					while ($order = $query_order->fetchRow()) {
						$item_id = stripslashes($order['item_id']);
						$quantity = stripslashes($order['quantity']);
						// Query item stock
						$query_items = $database->query("SELECT stock FROM " .TABLE_PREFIX."mod_bakery_items WHERE item_id = '$item_id'");
						$item = $query_items->fetchRow();
						$stock = stripslashes($item['stock']);
						// Only use stock admin if stock is not blank
						if (is_numeric($stock) && $stock != "") {
							// Update stock to required quantity
							$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET stock = stock + '$quantity' WHERE item_id = '$item_id'");
						}
					}
				}
				
				// ...then delete not submitted orders
				$database->query("DELETE FROM " .TABLE_PREFIX."mod_bakery_customer WHERE order_id = '$outdated_order_id' AND submitted = 'no'");
				$database->query("DELETE FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id = '$outdated_order_id'");
			}
		}			
	}
	$order_id = $_SESSION['bakery']['order_id'];



	//  SHOPPING CART FUNCTIONS
	//  ***********************


	// PUT ITEM INTO THE CART
	if (isset($_POST['add_to_cart']) && ($_POST['add_to_cart'] != "")) {
		
		// Get item ID and quantity ( -> $value)
		$sql_result1 = $database->query("SELECT * FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id = '$order_id'");
		
		foreach ($_POST as $field => $value) {
			// Error message if quantity < 1
			if (substr($field,0,4) == "item" && $value < 1) {
				$cart_error[] = $MOD_BAKERY['ERR_QUANTITY_ZERO'];
			}
			if (substr($field,0,4) == "item" && $value > 0) {
				// Get item_id
				$item_id = substr($field,4,strlen($field)-4);
				// Get item attributes and make comma separated string
				if (isset($_POST['attribute'][0])) {
					$attributes = implode(",", $_POST['attribute']);
				} else {
				// If no attribute is given set it to "none"
					$attributes = "none";
				}
				// free text area
				if (isset($_POST['view_item_textarea'])) {
					$textarea = htmlspecialchars ($_POST['view_item_textarea']);
				} else {
				// If no attribute is given create empty variable
					$textarea = "";
				}

				//  Error message if item is in cart already
				while ($row1 = $sql_result1->fetchRow()) {
					if ($row1['item_id'] == $item_id && $row1['attributes'] == $attributes) {
						$cart_error[] = $MOD_BAKERY['ERR_ITEM_EXISTS'];
						include('view_cart.php');
						echo $end_of_wrapper;  // End of bakery wrapper
						return;
					}
				}
				
				// Get item price, sku, stock and tax_rate
				$sql_result2 = $database->query("SELECT title, price, sku, stock, tax_rate FROM " .TABLE_PREFIX."mod_bakery_items WHERE item_id = '$item_id'");
				$row2 = $sql_result2->fetchRow();
				$row2 = array_map('stripslashes', $row2);
				$title = $row2['title'];
				$sku = $row2['sku'];
				$price = $row2['price'];
				$tax_rate = $row2['tax_rate'];
				$stock = $row2['stock'];
				$quantity = $value;
				
				// Only use stock admin if stock is not blank
				if (is_numeric($stock) && $stock != "") {
					// If item is short of stock show error message
					if ($setting_out_of_stock_orders) {

						// Case: Allow out of stock orders
						if ($stock < $value) {
							$cart_error[] = "{$MOD_BAKERY['TXT_SHORT_OF_STOCK_SUBSEQUENT_DELIVERY']}!<br /><b>$stock</b> {$MOD_BAKERY['TXT_ITEMS']} <b>$title</b> {$MOD_BAKERY['TXT_AVAILABLE_QUANTITY']}.";
						}
						// Update stock
						$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET stock = stock - '$value' WHERE item_id = '$item_id'");

					} else {
						// Case: No out of stock orders
						// If item is short of stock...
						if ($stock <= $value) {
							// ...set quantity to remaining stock
							$quantity = $stock;
							// Show error message
							if ($stock < $value) {
								$cart_error[] = "<b>$quantity</b> {$MOD_BAKERY['TXT_ITEMS']} <b>$title</b> {$MOD_BAKERY['TXT_AVAILABLE_QUANTITY']}.<br />{$MOD_BAKERY['TXT_SHORT_OF_STOCK_QUANTITY_CAPPED']}!";
							}
						}
						// Update stock
						$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET stock = stock - '$quantity' WHERE item_id = '$item_id'");
					}
				}
				// Insert ordered item data into db
				if ($quantity > 0) {
					$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_order (order_id, item_id, attributes, textarea, sku, quantity, price, tax_rate) VALUES ('$order_id', '$item_id', '$attributes', '$textarea', '$sku', '$quantity', '$price', '$tax_rate')");
				}
			}
		}

		// If required skip cart
		if ($setting_skip_cart == "yes") {
			include('view_overview.php');
			echo $end_of_wrapper;  // End of bakery wrapper
			unset($_SESSION['bakery']['minicart']);
			return;
		} else {
			// Show cart
			include('view_cart.php');
			echo $end_of_wrapper;  // End of bakery wrapper
			return;
		}
	}


	// UPDATE CART
	elseif (isset($_POST['update_cart']) && ($_POST['update_cart'] != "")) {
		// Update quantities in db
		foreach ($_POST['quantity'] as $item_id => $attributes) {
			foreach ($_POST['quantity'][$item_id] as $attributes => $quantity) {
				$item_id = strip_tags($item_id);
				$attributes = strip_tags($attributes);
				$quantity = strip_tags($quantity);

				// Query item stock
				$query_items = $database->query("SELECT title, stock FROM " .TABLE_PREFIX."mod_bakery_items WHERE item_id = '$item_id'");
				$item = $query_items->fetchRow();
				$title = stripslashes($item['title']);
				$stock = stripslashes($item['stock']);

				// Only use stock admin if stock is not blank
				if (is_numeric($stock) && $stock != "") {
					// If item is short of stock show error message
					if ($setting_out_of_stock_orders) {

						// Case: Allow out of stock orders
						// Query current item quantity 
						$query_order = $database->query("SELECT quantity FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id = '$order_id' AND item_id = '$item_id' AND attributes = '$attributes'");
						$order = $query_order->fetchRow();
						$quantity_current = stripslashes($order['quantity']);
						// Calculate difference
						$quantity_diff = $quantity - $quantity_current;
						// If items are short of stock show error message
						if ($stock < $quantity_diff) {
							$cart_error[0] = "{$MOD_BAKERY['TXT_SHORT_OF_STOCK_SUBSEQUENT_DELIVERY']}!";
							$available_quantity = $stock + $quantity_current;
							$cart_error[] = "<b>$available_quantity</b> {$MOD_BAKERY['TXT_ITEMS']} <b>$title</b> {$MOD_BAKERY['TXT_AVAILABLE_QUANTITY']}.";
						}						
						// Update stock to required quantity
						$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET stock = stock - '$quantity_diff' WHERE item_id = '$item_id'");

					} else {
						// Case: No out of stock orders
						// Query current item quantity 
						$query_order = $database->query("SELECT quantity FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id = '$order_id' AND item_id = '$item_id' AND attributes = '$attributes'");
						$order = $query_order->fetchRow();
						$quantity_current = stripslashes($order['quantity']);
						// Calculate difference
						$quantity_diff = $quantity - $quantity_current;
						// If item is short of stock...
						if ($stock <= $quantity_diff) {
							// Set quantity to sum of remaining stock and current number of items in cart
							$quantity = $stock + $quantity_current;
							// Update stock abd deactivate item
							$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET stock = '0' WHERE item_id = '$item_id'");
							// Compose error message
							if ($stock < $quantity_diff) {
								$cart_error[] = "<b>$quantity</b> {$MOD_BAKERY['TXT_ITEMS']} <b>$title</b> {$MOD_BAKERY['TXT_AVAILABLE_QUANTITY']}.";
							}
						}
						// Stock is large enough
						else {
							// Update stock to required quantity and make sure item is activated
							$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET stock = stock - '$quantity_diff' WHERE item_id = '$item_id'");
						}
					}
				}
				// Update item order quantity
				$database->query("UPDATE " .TABLE_PREFIX."mod_bakery_order SET quantity = '$quantity' WHERE order_id = '$order_id' AND item_id = '$item_id' AND attributes = '$attributes'");
			}
		}

		// Add opening paragraph to the error message
		if (isset($cart_error) && !$setting_out_of_stock_orders) {
			$cart_error[] = "{$MOD_BAKERY['TXT_SHORT_OF_STOCK_QUANTITY_CAPPED']}!";
		}

		// Delete ordered items with quantity 0
		$database->query("DELETE FROM " .TABLE_PREFIX."mod_bakery_order WHERE quantity = '0' AND order_id = '$order_id'");
		
		// Enable success message to show in view_cart.php
		$cart_success = true;

		// Show cart
		include('view_cart.php');
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}


	// SHOW CUSTOMER ADDRESS FORM ONLY
	elseif (isset($_POST['submit_order']) && ($_POST['submit_order'] != "")) {
		if ($setting_shipping_form == "hideable" || $setting_shipping_form == "always") {
			$_SESSION['bakery']['ship_form'] = "yes";
		}
		include('view_form.php');
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}


	// SHOW CUSTOMER ADDRESS FORM BUT HIDE SHIPPING ADDRESS FORM	
	elseif (isset($_POST['hide_ship_form']) && ($_POST['hide_ship_form'] != "")) {
		unset($_SESSION['bakery']['ship_form']);
		include('view_form.php');
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}


	// SHOW CUSTOMER ADDRESS FORM AND ADD SHIPPING ADDRESS FORM	
	elseif (isset($_POST['add_ship_form']) && ($_POST['add_ship_form'] != "")) {
		$_SESSION['bakery']['ship_form'] = "yes";
		include('view_form.php');
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}


	// SHOW CART ONLY
	else {
		include('view_cart.php');
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}
}

	
	
//  PROCESS ORDER
//  *************

elseif (isset($_POST['summary']) && isset($_SESSION['bakery']['order_id']) && ($_SESSION['bakery']['order_id'] != "")) {

	// Clean post array
	$_POST = array_map('strip_tags', $_POST);

	// Check for blank fields
	foreach ($_POST as $field => $value) {
		if ($value == "") {
			$blanks[] = $field;
		}
	}

	// If blank fields show error message
	if (isset($blanks)) {
		$form_error = $MOD_BAKERY['ERR_FIELD_BLANK'];
		$error_bg = $blanks;
		extract($_POST);
		include('view_form.php');
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}

	// If email fields do not match show error message
	if ($_POST['cust_confirm_email']) {
	if ($_POST['cust_email'] !== $_POST['cust_confirm_email']) {
		$error_bg[] = 'cust_email';
		$error_bg[] = 'cust_confirm_email';
		$errors[] = $MOD_BAKERY['ERR_EMAILS_NOT_MATCHED'];
	}
	}
		
	// Check the textfields
	$add_chars = $MOD_BAKERY['ADD_REGEXP_CHARS'];
	
	foreach ($_POST as $field => $value) {
		if ($field != "checkout_ship_pay") {
			$field = strip_tags($field);
			$value = strip_tags($value);
		
			if (strpos($field, 'first_name') !== false) {
				if (!preg_match('#^[A-Za-z'.$add_chars.' -]{1,50}$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_NAME'];
				}
			}

			if (strpos($field, 'last_name') !== false) {
				if (!preg_match('#^[A-Za-z'.$add_chars.' -]{1,50}$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_NAME'];
				}
			}

			if (strpos($field, 'street') !== false) {
				if (!preg_match('#^[A-Za-z0-9.'.$add_chars.' -]{1,50}$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_STREET'];
				}
			}

			if (strpos($field, 'city') !== false) {
				if (!preg_match('#^[A-Za-z.'.$add_chars.' -]{1,50}$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_CITY'];
				}
			}

			if (strpos($field, 'state') !== false) {
				if (!preg_match('#^[A-Za-z0-9.'.$add_chars.' -]{1,50}$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_STATE'];
				}
			}

			if (strpos($field, 'country') !== false) {
				if (!preg_match('#^[A-Z]{2}$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_COUNTRY'];
				}
			}

			if (strpos($field, 'email') !== false) {
				if (!preg_match('#^.+@.+\..+$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_EMAIL'];
				}
			}

			if (strpos($field, 'zip') !== false) {
				if (!preg_match('#^[A-Za-z0-9 -]{4,10}$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_ZIP'];
				}
			}

			if (strpos($field, 'phone') !== false) {
				if (!preg_match('#^[0-9)(xX +.-]{7,20}$#', $value)) {
					$error_bg[] = $field;
					$errors[] = htmlspecialchars($value, ENT_QUOTES).' '.$MOD_BAKERY['ERR_INVAL_PHONE'];
				}
			}
	
			$$field = strip_tags(trim($value));
		}
	}
	
 
	if (@is_array($errors)) {  
		$form_error = "";
		foreach ($errors as $value) {
			$form_error .= $value."<br />";
		}
		$form_error .= "<br />".$MOD_BAKERY['ERR_INVAL_TRY_AGAIN'];
		include('view_form.php');
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}


	// If all fields correct, write into db
	foreach ($_POST as $field => $value) {
		if ($field != 'summary' && $field != 'cust_confirm_email') {
			$field = $admin->add_slashes(strip_tags($field));
			$value = $admin->add_slashes(strip_tags($value));
			$updates[] = "$field = '$value'";
		}
	}
	// Make update string
	if (isset($_SESSION['USER_ID'])) {
		$update_string = "user_id = '{$_SESSION['USER_ID']}',".implode($updates,",");
	} else {
		$update_string = implode($updates,",");
	}
	// If ship form has not been used, clear ship fields in db
	if (!isset($_SESSION['bakery']['ship_form'])) $update_string .= ", ship_first_name = '', ship_last_name = '', ship_street = '', ship_city = '', ship_state = '', ship_country = '', ship_zip= ''";
	// Update db
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_customer SET $update_string WHERE order_id = '{$_SESSION['bakery']['order_id']}'");
	include('view_summary.php');
	echo $end_of_wrapper;  // End of bakery wrapper
	return;
}



//  SHOW ORDER FORM FOR MODIFYING
//  *****************************

elseif (isset($_POST['modify_address']) && isset($_SESSION['bakery']['order_id']) && ($_SESSION['bakery']['order_id'] != "")) {
	include('view_form.php');
	echo $end_of_wrapper;  // End of bakery wrapper
	return;
}



//  QUIT ORDER
//  **********

elseif (isset($_POST['quit_order']) && isset($_SESSION['bakery']['order_id']) && ($_SESSION['bakery']['order_id'] != "")) {

	// Get order id
	$order_id = $_SESSION['bakery']['order_id'];
	// First put not sold items back to stock...
	$query_order = $database->query("SELECT item_id, quantity FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id = '$order_id'");
	if ($query_order->numRows() > 0) {
		while ($order = $query_order->fetchRow()) {
			$item_id = stripslashes($order['item_id']);
			$quantity = stripslashes($order['quantity']);
			// Query item stock
			$query_items = $database->query("SELECT stock FROM " .TABLE_PREFIX."mod_bakery_items WHERE item_id = '$item_id'");
			$item = $query_items->fetchRow();
			$stock = stripslashes($item['stock']);
			// Only use stock admin if stock is not blank
			if (is_numeric($stock) && $stock != "") {
				// Update stock to required quantity
				$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET stock = stock + '$quantity' WHERE item_id = '$item_id'");
			}
		}
	}

	// Delete the db records not needed any more
	$database->query("DELETE FROM " .TABLE_PREFIX."mod_bakery_customer WHERE order_id='$order_id' AND submitted='no'");
	$database->query("DELETE FROM " .TABLE_PREFIX."mod_bakery_order WHERE order_id='$order_id'");

	// Clean up the session array
	unset($_SESSION['bakery']);

	// Order canceled by user => show message
	echo "<div class='mod_bakery_success_f' style='margin-top: 50px'><p>{$MOD_BAKERY['TXT_CANCEL']}</p>";
	echo "<p>{$MOD_BAKERY['TXT_DELETED']}</p>";
	echo "<p style='font-weight: bold'>{$MOD_BAKERY['TXT_THANK_U_VISIT']}</p></div>";
	echo $end_of_wrapper;  // End of bakery wrapper 
	return;
}



// SUBMIT FINAL ORDER
// ******************

elseif (isset($_POST['checkout']) && isset($_SESSION['bakery']['order_id']) && ($_SESSION['bakery']['order_id'] != "")) {
		
	// Customer has agreed to terms & conditions => submit final order
	//if (isset($_POST['agree']) && $_POST['agree'] == "yes") {
	if (
		(isset($_POST['agree_tac']) && ($_POST['agree_tac'] == "yes")) &&
		(isset($_POST['agree_cancellation']) && ($_POST['agree_cancellation'] == "yes")) &&
		(isset($_POST['agree_privacy']) && ($_POST['agree_privacy'] == "yes"))
		) {	
		// View payment methods
		include('view_pay.php');
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}

	// JavaScript = off fallback
	// Error message if customer has not agreed to the terms and conditions, then show it again
	else {
		// Determine which to show
		$terms_of_agreement = "";
		$terms_of_agreement .= !((isset($_POST['agree_tac']) && ($_POST['agree_tac'] == "yes"))) ? "<input type='checkbox' name='agree_tac' id='agree_tac' value='yes' />&nbsp;&nbsp;\n<a href='$setting_tac_url' target='_blank'>{$MOD_BAKERY['TXT_AGREE_TAC']}<a>\n" : "";
		$terms_of_agreement .= !((isset($_POST['agree_cancellation']) && ($_POST['agree_cancellation'] == "yes"))) ? "<input type='checkbox' name='agree_cancellation' id='agree_cancellation' value='yes' />&nbsp;&nbsp;\n<a href='$setting_cancellation_url' target='_blank'>{$MOD_BAKERY['TXT_AGREE_CANCELLATION']}<a>\n" : "";
		$terms_of_agreement .= !((isset($_POST['agree_privacy']) && ($_POST['agree_privacy'] == "yes"))) ? "<input type='checkbox' name='agree_privacy' id='agree_privacy' value='yes' />&nbsp;&nbsp;\n<a href='$setting_privacy_url' target='_blank'>{$MOD_BAKERY['TXT_AGREE_PRIVACY']}<a>\n" : "";
		
		echo "<div class='mod_bakery_error_f' style='margin-top: 50px'><p>{$MOD_BAKERY['ERR_AGREE']}</p></div>\n";
		echo "<form action='$setting_continue_url' method='post'>\n";
		echo "<p style='text-align: center; font-weight: bold; margin-top: 40px'>";
		echo $terms_of_agreement;
		echo "</p>";
		echo "<p style='text-align: center'><input type='submit' name='checkout' class='mod_bakery_bt_order_f' value='{$MOD_BAKERY['TXT_SUBMIT_ORDER']}' /></p>\n";
		echo "</form>";
		echo $end_of_wrapper;  // End of bakery wrapper
		return;
	}
}



//  PAYMENT METHOD ERROR HANDLING
//  *****************************

elseif (isset($_GET['pay_error']) && is_numeric($_GET['pay_error'])) {
	// View payment methods
	include('view_pay.php');
	echo $end_of_wrapper;  // End of bakery wrapper
	return;
}



//  CHECK PAYMENT STATUS AND VIEW CONFIRMATION
//  ******************************************

elseif (isset($_SESSION['bakery']['order_id']) && !empty($_SESSION['bakery']['order_id']) &&
      ((isset($_REQUEST['payment_method']) && in_array($_REQUEST['payment_method'], $setting_payment_methods)) ||
      (isset($_REQUEST['pm']) && in_array($_REQUEST['pm'], $setting_payment_methods)))) {

	// Get the payment method
	if (isset($_REQUEST['payment_method'])) {
		$payment_method = $_REQUEST['payment_method'];
	} elseif (isset($_REQUEST['pm'])) {
		$payment_method = $_REQUEST['pm'];
	} else {
		$payment_method = false;
	}

	// Check the status of the payment (error/canceled/success/pending)
	if (is_string($payment_method)) {
		require(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/check_payment.php');
	} else {
		$payment_status = "error";
	}

	// Display error/canceled message OR in case of success/pending display confirmation and send emails
	require('view_confirmation.php');
		
	echo $end_of_wrapper;  // End of bakery wrapper
	return;
}



// SET PAGE OFFLINE FOR MAINTENANCE
// ********************************

elseif ($setting_page_offline == "yes") {
	echo "<div class='mod_bakery_error_f' style='margin-top: 50px'><p>$setting_offline_text</p></div>";
	echo $end_of_wrapper;  // End of bakery wrapper
	return;
}



// SHOW OVERVIEW PAGE
// ******************

elseif (!defined('ITEM_ID') OR !is_numeric(ITEM_ID)) {
	include('view_overview.php');
	echo $end_of_wrapper;  // End of bakery wrapper
}



// SHOW ITEM DETAIL PAGE
// *********************

elseif (defined('ITEM_ID') AND is_numeric(ITEM_ID)) {
	include('view_item.php');
	echo $end_of_wrapper;  // End of bakery wrapper
}


?>