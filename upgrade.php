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
	if (!$inc) trigger_error(sprintf("[ <strong>%s</strong> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include class.secure.php 


require_once(WB_PATH.'/framework/functions.php');
$database = new database();


// Setup styles to help id errors
echo'
<style type="text/css">
.good {	color: green; }
.bad { color: red; }
.ok { color: blue; }
.warn { color: yellow; }
</style>
';

// get current module version from 'addons' table
if (!($query_addons = $database->query("SELECT `version` FROM `".TABLE_PREFIX."addons` WHERE `directory` = 'bakery'"))) {
	exit("ERROR: ".mysql_error());
}
$bakery_settings = $query_addons->fetchRow();

// UPGRADE TO VERSION 0.7 
// **********************

if ($bakery_settings['version'] < '0.7') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 0.7:</h3>';

	// Get ITEMS table to see what needs to be created
	$itemstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_items`");
	$items = $itemstable->fetchRow();
	
	
	// Adding new fields to the existing ITEMS table
	echo'<h4>Adding new fields to the items table</h4>';
	
	if (!array_key_exists('option_attributes', $items)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` ADD `option_attributes` TEXT NOT NULL AFTER `shipping`")) {
				echo '<p class="good">Database field option_attributes added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field option_attributes exists, update not needed</p>'; }
	
	
	if (!array_key_exists('option_name', $items)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` ADD `option_name` VARCHAR(255) NOT NULL DEFAULT '' AFTER `shipping`")) {
				echo '<p class="good">Database field option_name added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field option_name exists, update not needed</p>'; }
		
	
	
	
	// Get CUSTOMER table to see what needs to be created
	$customertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer`");
	$num_customers = $customertable->numRows();
	if ($num_customers == 0) {
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_customer (order_id) VALUES ('0')");
	}
	$customertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer`");
	$customer = $customertable->fetchRow();
	
	
	// Modifying fields or adding new fields to the existing CUSTOMER table
	echo'<h4>Modifying fields and adding new fields to the customer table</h4>';
	
	if (array_key_exists('cust_name', $customer)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` CHANGE `cust_name` `cust_first_name` VARCHAR(50)")) {
				echo '<p class="good">Changed database field cust_name to cust_first_name successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field cust_last_name exists, update not needed</p>'; }
	
	
	if (!array_key_exists('cust_last_name', $customer)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `cust_last_name` VARCHAR(50) AFTER `cust_first_name`")) {
				echo '<p class="good">Database field cust_last_name added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field cust_last_name exists, update not needed</p>'; }
	
	
	
	
	// Get ORDER table to see what needs to be created
	$ordertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_order`");
	$num_orders = $ordertable->numRows();
	if ($num_orders == 0) {
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_order (order_id) VALUES ('0')");
	}
	$ordertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_order`");
	$order = $ordertable->fetchRow();
	
	
	// Adding new field to the existing ORDER table
	echo'<h4>Adding new field to the order table</h4>';
	
	if (!array_key_exists('attribute', $order)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_order` ADD `attribute` VARCHAR(50) NOT NULL AFTER `item_id`")) {
				echo '<p class="good">Database field attribute added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field attribute exists, update not needed</p>'; }
	
	
	
	
	// Get SETTINGS table to see what needs to be created
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_settings`");
	if ($settings = $settingstable->fetchRow()) {
	
		// Adding new fields to the existing SETTINGS table
		echo'<h4>Adding new fields to the settings table</h4>';
		
		if (!array_key_exists('offline_text', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_settings` ADD `offline_text` TINYTEXT NOT NULL AFTER `page_id`")) {
					echo '<p class="good">Database field offline_text added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field offline_text exists, update not needed</p>'; }
		
		
		if (!array_key_exists('page_offline', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_settings` ADD `page_offline` ENUM('yes','no') NOT NULL DEFAULT 'no' AFTER `page_id`")) {
					echo '<p class="good">Database field page_offline added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field page_offline exists, update not needed</p>'; }
				
		
		if (array_key_exists('shop_url', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_settings` CHANGE `shop_url` `proceed_url` VARCHAR(255) NOT NULL")) {
					echo '<p class="good">Changed database field shop_url to proceed_url successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field proceed_url exists, update not needed</p>'; }
				
		
		if (array_key_exists('paypal_return', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_settings` DROP `paypal_return`")) {
					echo '<p class="good">Database field paypal_return deleted successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field paypal_return does not exist, update not needed</p>'; }
		
		
		if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_settings` CHANGE `shipping_method` `shipping_method` VARCHAR(20) NOT NULL")) {
			echo '<p class="good">Database field shipping_method changed successfully</p>';
		} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		
		
		if (!array_key_exists('free_shipping_msg', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_settings` ADD `free_shipping_msg` ENUM('show','hide') NOT NULL DEFAULT 'hide' AFTER `shipping_method`")) {
					echo '<p class="good">Database field free_shipping_msg added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field free_shipping_msg exists, update not needed</p>'; }
		
		
		if (!array_key_exists('free_shipping', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_settings` ADD `free_shipping` DECIMAL(6,2) NOT NULL AFTER `shipping_method`")) {
					echo '<p class="good">Database field free_shipping added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field free_shipping exists, update not needed</p>'; }
		
	} else {
		echo '<p class="warn">Database settings table does not exist, update not needed</p>';
		}
	
	echo"<br />";
	
	
	
	
	// Separat settings table into a general settings table and page settings table
	
	// Add new general settings table to the database
	echo'<h4>Adding new general settings table to the database</h4>';
	
	// Create new GENERAL SETTINGS table
	$mod_bakery = 'CREATE TABLE `'.TABLE_PREFIX.'mod_bakery_general_settings` ( '
			. '`shop_id` INT NOT NULL DEFAULT \'0\','
			. '`shop_name` VARCHAR(100) NOT NULL ,'
			. '`use_captcha` ENUM(\'yes\',\'no\') NOT NULL ,'
			. '`tac_url` VARCHAR(255) NOT NULL ,'
			. '`shop_email` VARCHAR(50) NOT NULL ,'
			. '`shop_country` VARCHAR(2) NOT NULL ,'
			. '`shop_currency` VARCHAR(3) NOT NULL ,'
			. '`bank_account` TEXT NOT NULL ,'
			. '`paypal_email` VARCHAR(50) NOT NULL ,'
			. '`paypal_page` VARCHAR(255) NOT NULL ,'
			. '`payment_method` VARCHAR(20) NOT NULL ,'
			. '`tax_rate` DECIMAL(5,3) NOT NULL ,'
			. '`tax_included` ENUM(\'included\',\'excluded\') NOT NULL ,'
			. '`shipping_domestic` DECIMAL(6,2) NOT NULL ,'
			. '`shipping_abroad` DECIMAL(6,2) NOT NULL ,'
			. '`shipping_method` VARCHAR(20) NOT NULL ,'
			. '`free_shipping` DECIMAL(6,2) NOT NULL ,'
			. '`free_shipping_msg` ENUM(\'show\',\'hide\') NOT NULL ,'
			. '`email_subject_advance` TEXT NOT NULL ,'
			. '`email_pay_advance` TEXT NOT NULL ,'
			. '`email_subject_paypal` TEXT NOT NULL ,'
			. '`email_paypal` TEXT NOT NULL ,'
			. 'PRIMARY KEY (shop_id)'
			. ' )';
	if ($database->query($mod_bakery)) {
		echo '<p class="good">Created new general_settings table successfully</p>';
	}
	else {
	echo '<p class="bad">'.mysql_error().'</p>';
	}
	
	// Get "old" settings to insert them into the new general_settings table
	if ($settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_settings` ORDER BY section_id DESC LIMIT 1")) {
		$settings = $settingstable->fetchRow();
		if ($settings['section_id'] == '') {
			echo '<p class="warn">No old settings in database to insert into general_settings table</p>';
		}
		else {
		echo '<p class="good">Got old settings from database section_id='.$settings['section_id'].' successfully</p>';
		}
	}
	else {
		echo '<p class="bad">'.mysql_error().'</p>';
	}
	
	// Set default general_settings 
	$shop_id = 0;
	$shop_name = $settings['shop_name'];
	$use_captcha = $settings['use_captcha'];
	$tac_url = $settings['tac_url'];
	$shop_email = $settings['shop_email'];
	$shop_country = $settings['shop_country'];
	$shop_currency = $settings['shop_currency'];
	$bank_account = $settings['bank_account'];
	$paypal_email = $settings['paypal_email'];
	$paypal_page = $settings['paypal_page'];
	$payment_method = "all";
	$tax_rate = $settings['tax_rate'];
	$tax_included = $settings['tax_included'];
	$shipping_domestic = $settings['shipping_domestic'];
	$shipping_abroad = $settings['shipping_abroad'];
	if ($settings['shipping_method'] == '') {$shipping_method = "flat"; } else {$shipping_method = $settings['shipping_method']; }
	if ($settings['free_shipping'] == '') {$settings['free_shipping'] = 0; } else {$free_shipping = $settings['free_shipping']; }
	if ($settings['free_shipping_msg'] == '') {$settings['free_shipping_msg'] = "all"; } else {$free_shipping_msg = $settings['free_shipping_msg']; }
	$email_subject_advance = $settings['email_subject_advance'];
	$email_pay_advance = $settings['email_pay_advance'];
	$email_subject_paypal = $settings['email_subject_paypal'];
	$email_paypal = $settings['email_paypal'];
	
	// Insert values into general_settings table 
	if ($database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_general_settings (shop_id, shop_name, use_captcha, tac_url, shop_email, shop_country, shop_currency, bank_account, paypal_email, paypal_page, payment_method, tax_rate, tax_included, shipping_domestic, shipping_abroad, shipping_method, free_shipping, free_shipping_msg, email_subject_advance, email_pay_advance, email_subject_paypal, email_paypal)
	VALUES ('$shop_id', '$shop_name', '$use_captcha', '$tac_url', '$shop_email', '$shop_country', '$shop_currency', '$bank_account', '$paypal_email', '$paypal_page', '$payment_method', '$tax_rate', '$tax_included', '$shipping_domestic', '$shipping_abroad', '$shipping_method', '$free_shipping', '$free_shipping_msg', '$email_subject_advance', '$email_pay_advance', '$email_subject_paypal', '$email_paypal')")) {
		echo '<p class="good">Added default settings into general_settings table successfully</p>';
	}
	else {
		echo '<p class="bad">'.mysql_error().'</p>';
	}
	
	
	
	
	// Insert default settings
	
	// Set default page_settings 
	if ($settings['page_offline'] == '') { $page_offline = "no"; } else {$page_offline = $settings['page_offline']; }
	if ($settings['offline_text'] == '') {
		if (LANGUAGE_LOADED) {
			include(WB_PATH.'/modules/bakery/languages/EN.php');
			if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
				include(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
			}
		}
		$offline_text  = $MOD_BAKERY['ERR_OFFLINE_TEXT'];
	}
	else {
		$offline_text = $settings['offline_text'];
	}
	
	// Adding default settings to the new fields
	$query_dates = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_settings WHERE section_id != 0 and page_id != 0");
	while ($result = $query_dates->fetchRow()) {
	
		echo '<h4>Adding default settings to database for bakery section_id='.$result['section_id'].'</h4>';
		$section_id = $result['section_id'];
	
		if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_settings` SET `page_offline` = '$page_offline' WHERE `section_id` = $section_id")) {
			echo '<p class="good">Database data page_offline added successfully</p>';
		}
		echo '<p class="bad">'.mysql_error().'</p>';
	
			
		if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_settings` SET `offline_text` = '$offline_text' WHERE `section_id` = $section_id")) {
			echo '<p class="good">Database data offline_text added successfully</p>';
		}
		echo '<p class="bad">'.mysql_error().'</p>';
	}
	
	echo"<br />";
	
	
	
	
	// Rename settings table to page_settings table
	echo'<h4>Renaming settings table to page_settings table</h4>';
	
	if ($database->query("RENAME TABLE `".TABLE_PREFIX."mod_bakery_settings` TO `".TABLE_PREFIX."mod_bakery_page_settings`")) {
		echo '<p class="good">Renamed settings table to page_settings table successfully</p>';
	}
	else {
	echo '<p class="bad">'.mysql_error().'</p>';
	}
	
	// Delete all fields which have been moved to general_settings table
	if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_page_settings` DROP `shop_name`, DROP `tac_url`, DROP `use_captcha`, DROP `shop_email`, DROP `shop_country`, DROP `shop_currency`, DROP `bank_account`, DROP `paypal_email`, DROP `paypal_page`, DROP `tax_rate`, DROP `tax_included`, DROP `shipping_domestic`, DROP `shipping_abroad`, DROP `shipping_method`, DROP `free_shipping`, DROP `free_shipping_msg`, DROP `email_subject_advance`, DROP `email_pay_advance`, DROP `email_subject_paypal`, DROP `email_paypal`")) {
		echo '<p class="good">Deleted all fields of page_settings (which have been created newly in the general_settings table) successfully</p>';
	}
	else {
	echo '<p class="bad">'.mysql_error().'</p>';
	}
}




// UPGRADE TO VERSION 0.8 
// **********************

if ($bakery_settings['version'] < '0.8') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 0.8:</h3>';

	// Get ITEMS table to see what needs to be created
	$itemstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_items`");
	$items = $itemstable->fetchRow();
	
	
	// Adding new fields to the existing ITEMS table
	echo'<h4>Adding new field to the items table</h4>';
	
	if (!array_key_exists('tax_rate', $items)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` ADD `tax_rate` DECIMAL(5,3) NOT NULL AFTER `shipping`")) {
				echo '<p class="good">Database field tax_rate added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field tax_rate exists, update not needed</p>'; }		
	
	
	
	// Get CUSTOMER table to see what needs to be created
	$customertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer`");
	$num_customers = $customertable->numRows();
	if ($num_customers == 0) {
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_customer (order_id) VALUES ('0')");
	}
	$customertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer`");
	$customer = $customertable->fetchRow();
	
	
	// Modifying fields or adding new fields to the existing CUSTOMER table
	echo'<h4>Adding new fields to the customer table</h4>';
	
	if (!array_key_exists('user_id', $customer)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `user_id` INT(6) NOT NULL AFTER `submitted`")) {
				echo '<p class="good">Database field user_id added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field user_id exists, update not needed</p>'; }


	if (!array_key_exists('cust_state', $customer)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `cust_state` VARCHAR(50) NOT NULL AFTER `cust_city`")) {
				echo '<p class="good">Database field cust_state added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field cust_state exists, update not needed</p>'; }
	
	
	
	// Get ORDER table to see what needs to be created
	$ordertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_order`");
	$num_orders = $ordertable->numRows();
	if ($num_orders == 0) {
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_order (order_id) VALUES ('0')");
	}
	$ordertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_order`");
	$order = $ordertable->fetchRow();
	
	
	// Adding new field to the existing ORDER table
	echo'<h4>Adding new field to the order table</h4>';
	
	if (!array_key_exists('tax_rate', $order)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_order` ADD `tax_rate` DECIMAL(5,3) NOT NULL AFTER `price`")) {
				echo '<p class="good">Database field attribute added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field tax_rate exists, update not needed</p>'; }
	
	
	
	
	// Get GENERAL SETTINGS table to see what needs to be created
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	$settings = $settingstable->fetchRow();

	// Adding new fields to the existing SETTINGS table
	echo'<h4>Adding new fields to the general_settings table</h4>';
	
	if (!array_key_exists('zip_location', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `zip_location` ENUM('inside','end') NOT NULL DEFAULT 'inside' AFTER `shop_country`")) {
				echo '<p class="good">Database field zip_location added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field zip_location exists, update not needed</p>'; }
	
	
	if (!array_key_exists('state_field', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `state_field` ENUM('show','hide') NOT NULL DEFAULT 'hide' AFTER `shop_country`")) {
				echo '<p class="good">Database field state_field added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field state_field exists, update not needed</p>'; }
	
	
	if (!array_key_exists('tax_rate2', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `tax_rate2` DECIMAL(5,3) NOT NULL AFTER `tax_rate`")) {
				echo '<p class="good">Database field tax_rate2 added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field tax_rate2 exists, update not needed</p>'; }


	if (!array_key_exists('tax_rate1', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `tax_rate1` DECIMAL(5,3) NOT NULL AFTER `tax_rate`")) {
				echo '<p class="good">Database field tax_rate1 added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field tax_rate1 exists, update not needed</p>'; }


	if (!array_key_exists('zone_countries', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `zone_countries` TEXT NOT NULL AFTER `shipping_abroad`")) {
				echo '<p class="good">Database field zone_countries added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field zone_countries exists, update not needed</p>'; }


	if (!array_key_exists('shipping_zone', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `shipping_zone` DECIMAL(6,2) NOT NULL AFTER `shipping_abroad`")) {
				echo '<p class="good">Database field shipping_zone added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field shipping_zone exists, update not needed</p>'; }

	echo"<br />";
	
	
	// Insert default settings into items table

	// Get "old" settings
	if ($settingstable = $database->query("SELECT tax_rate FROM `".TABLE_PREFIX."mod_bakery_general_settings`")) {
		$settings = $settingstable->fetchRow();
		if ($settings['tax_rate'] == '') {
			echo '<p class="warn">No old tax_rate setting in database to insert into items table</p>';
		}
		else {
			echo '<p class="good">Got old tax_rate setting (<strong>'.$settings['tax_rate'].'%</strong>) from database to insert into items table</p>';
		}
	}
	else {
		echo '<p class="bad">'.mysql_error().'</p>';
	}

	// Insert values into general_settings table 
	if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_items` SET `tax_rate` = '$tax_rate'")) {
		echo '<p class="good">Added default tax_rate setting into items table successfully</p>';
	}
	else {
		echo '<p class="bad">'.mysql_error().'</p>';
	}
}

	

	
// UPGRADE TO VERSION 0.8.1 
// ************************

if ($bakery_settings['version'] < '0.81') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 0.8.1:</h3>';
	
	// Get SETTINGS table to see what needs to be changed
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_page_settings`");
	if ($settings = $settingstable->fetchRow()) {
	
		if (array_key_exists('proceed_url', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_page_settings` CHANGE `proceed_url` `continue_url` VARCHAR(255) NOT NULL")) {
					echo '<p class="good">Changed database field proceed_url to continue_url successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field continue_url exists, update not needed</p>'; }
		
	}
}

	

	
// UPGRADE TO VERSION 0.8.3
// ************************

if ($bakery_settings['version'] < '0.83') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 0.8.3:</h3>';
	
	// Get SETTINGS table to see what needs to be changed
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	if ($settings = $settingstable->fetchRow()) {
	
		if (!array_key_exists('tax_rate_shipping', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `tax_rate_shipping` DECIMAL(5,3) NOT NULL AFTER `tax_included`")) {
					echo '<p class="good">Database field tax_rate_shipping added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field tax_rate_shipping exists, update not needed</p>'; }
		
	}
}

	

	
// UPGRADE TO VERSION 0.9
// ************************

if ($bakery_settings['version'] < '0.9') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 0.9:</h3>';



	// Add new options table to the database
	echo'<h4>Adding new options table to the database</h4>';
	
	// Create new OPTIONS table
	$mod_bakery = 'CREATE TABLE `'.TABLE_PREFIX.'mod_bakery_options` ( '
			. ' `option_id` INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY, '
			. ' `option_name` VARCHAR(50) NOT NULL'
			. ' )';
	if ($database->query($mod_bakery)) {
		echo '<p class="good">Created new options table successfully</p>';
	}
	else {
	echo '<p class="bad">'.mysql_error().'</p>';
	}


	// Add new attributes table to the database
	echo'<h4>Adding new attributes table to the database</h4>';
	
	// Create new ATTRIBUTES table
	$mod_bakery = 'CREATE TABLE `'.TABLE_PREFIX.'mod_bakery_attributes` ( '
			. ' `attribute_id` INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY, '
			. ' `option_id` INT(6) NOT NULL, '
			. ' `attribute_name` VARCHAR(50) NOT NULL'
			. ' )';
	if ($database->query($mod_bakery)) {
		echo '<p class="good">Created new attributes table successfully</p>';
	}
	else {
	echo '<p class="bad">'.mysql_error().'</p>';
	}


	// Add new items attributes table to the database
	echo'<h4>Adding new item attributes table to the database</h4>';
	
	// Create new ITEMS ATTRIBUTES table
	$mod_bakery = 'CREATE TABLE `'.TABLE_PREFIX.'mod_bakery_item_attributes` ( '
			. ' `assign_id` INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY, '
			. ' `item_id` INT(6) NOT NULL, '
			. ' `option_id` INT(6) NOT NULL, '
			. ' `attribute_id` INT(6) NOT NULL, '
			. ' `price` DECIMAL(9,2) NOT NULL, '
			. ' `operator` VARCHAR(1) NOT NULL'
			. ' )';
	if ($database->query($mod_bakery)) {
		echo '<p class="good">Created new item attributes table successfully</p>';
	}
	else {
	echo '<p class="bad">'.mysql_error().'</p>';
	}
	
	

	// There has to be at least one row in the ITEMS table - if not, insert blank row
	$itemstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_items`");
	$num_items = $itemstable->numRows();
	if ($num_items == 0) {
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_items (item_id) VALUES ('0')");
	}

	// Get ITEMS table to see what needs to be added
	$itemstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_items`");
	if ($item = $itemstable->fetchRow()) {

		if (!array_key_exists('stock', $item)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` ADD `stock` VARCHAR(20) NOT NULL DEFAULT '' AFTER `sku`")) {
					echo '<p class="good">Database field stock added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field stock exists, update not needed</p>'; }
	}


	// There has to be at least one row in the CUSTOMER table - if not, insert blank row
	$customertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer`");
	$num_customers = $customertable->numRows();
	if ($num_customers == 0) {
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_customer (order_id) VALUES ('0')");
	}

	// Get CUSTOMER table to see what needs to be changed
	$customertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer`");
	if ($customer = $customertable->fetchRow()) {

		if (array_key_exists('submitted', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` CHANGE `submitted` `submitted` VARCHAR(3) NOT NULL")) {
					echo '<p class="good">Changed database field submitted to type VARCHAR(3) successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		}

		if (!array_key_exists('status', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `status` VARCHAR(20) NOT NULL DEFAULT 'none' AFTER `submitted`")) {
					echo '<p class="good">Database field status added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field status exists, update not needed</p>'; }
		
		if (!array_key_exists('invoice', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `invoice` TEXT NOT NULL AFTER `cust_phone`")) {
					echo '<p class="good">Database field invoice added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field invoice exists, update not needed</p>'; }

		if (!array_key_exists('ship_zip', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `ship_zip` VARCHAR(10) NOT NULL AFTER `cust_phone`")) {
					echo '<p class="good">Database field ship_zip added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field ship_zip exists, update not needed</p>'; }
		
		if (!array_key_exists('ship_country', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `ship_country` VARCHAR(2) NOT NULL AFTER `cust_phone`")) {
					echo '<p class="good">Database field ship_country added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field ship_country exists, update not needed</p>'; }
		
		if (!array_key_exists('ship_state', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `ship_state` VARCHAR(50) NOT NULL AFTER `cust_phone`")) {
					echo '<p class="good">Database field ship_state added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field ship_state exists, update not needed</p>'; }
	
		if (!array_key_exists('ship_city', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `ship_city` VARCHAR(50) NOT NULL AFTER `cust_phone`")) {
					echo '<p class="good">Database field ship_city added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field ship_city exists, update not needed</p>'; }
	
		if (!array_key_exists('ship_street', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `ship_street` VARCHAR(50) NOT NULL AFTER `cust_phone`")) {
					echo '<p class="good">Database field ship_street added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field ship_street exists, update not needed</p>'; }
	
		if (!array_key_exists('ship_last_name', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `ship_last_name` VARCHAR(50) NOT NULL AFTER `cust_phone`")) {
					echo '<p class="good">Database field ship_last_name added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field ship_last_name exists, update not needed</p>'; }
	
		if (!array_key_exists('ship_first_name', $customer)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `ship_first_name` VARCHAR(50) NOT NULL AFTER `cust_phone`")) {
					echo '<p class="good">Database field ship_first_name added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field ship_first_name exists, update not needed</p>'; }
				
	}


	// There has to be at least one row in the ORDER table - if not, insert blank row
	$ordertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_order`");
	$num_order = $ordertable->numRows();
	if ($num_order == 0) {
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_order (order_id) VALUES ('0')");
	}

	// Get ORDER table to see what needs to be added
	$ordertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_order`");
	if ($order = $ordertable->fetchRow()) {

		if (array_key_exists('attribute', $order)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_order` CHANGE `attribute` `attributes` VARCHAR(50) NOT NULL")) {
				echo '<p class="good">Changed database field attribute to attribute<strong>s</strong> successfully</p>';
			}
			else {
				echo '<p class="bad">'.mysql_error().'</p>';
			}

			if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_order` SET `attributes` = ''")) {
				echo '<p class="good">Deleted outdated order attributes successfully</p>';
			}
			else {
				echo '<p class="bad">'.mysql_error().'</p>';
			}
		}
	}


	// Get GENERAL SETTINGS table to see what needs to be changed
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	if ($settings = $settingstable->fetchRow()) {
	
		if (!array_key_exists('shipping_form', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `shipping_form` VARCHAR(10) NOT NULL AFTER `shop_country`")) {
					echo '<p class="good">Database field shipping_form added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field shipping_form exists, update not needed</p>'; }
		
		if (!array_key_exists('invoice', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `invoice` TEXT NOT NULL AFTER `email_paypal`")) {
					echo '<p class="good">Database field invoice added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field invoice exists, update not needed</p>'; }

		if (!array_key_exists('email_invoice', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `email_invoice` TEXT NOT NULL AFTER `email_paypal`")) {
					echo '<p class="good">Database field email_invoice added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field email_invoice exists, update not needed</p>'; }

		if (!array_key_exists('email_subject_invoice', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `email_subject_invoice` TEXT NOT NULL AFTER `email_paypal`")) {
					echo '<p class="good">Database field email_subject_invoice added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field email_subject_invoice exists, update not needed</p>'; }		

	}


	// Insert default settings into general settings table
	if (LANGUAGE_LOADED) {
		include(WB_PATH.'/modules/bakery/languages/EN.php');
    	include(WB_PATH.'/modules/bakery/payment_methods/invoice/languages/EN.php');
		if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
			include(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
		}
		$payment_method = "invoice";
		if (file_exists(WB_PATH.'/modules/bakery/payment_methods/invoice/languages/'.LANGUAGE.'.php')) {
			include(WB_PATH.'/modules/bakery/payment_methods/invoice/languages/'.LANGUAGE.'.php');
		}
	}

	$email_subject_invoice = $MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'];
	$email_invoice = $MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'];
	$invoice = $admin->add_slashes($MOD_BAKERY[$payment_method]['INVOICE_TEMPLATE']);

	if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_general_settings` SET `email_subject_invoice` = '$email_subject_invoice', `email_invoice` = '$email_invoice', `invoice` = '$invoice'")) {
		echo '<p class="good">Added default general settings successfully</p>';
	}
	else {
		echo '<p class="bad">'.mysql_error().'</p>';
	}


	// General upgrade note
	echo '
<div style="margin: 15px 0; padding: 10px 10px 10px 60px; text-align: left; color: red; border: solid 1px red; background-color: #FFDCD9; background-image: url('.WB_URL.'/modules/bakery/images/information.gif); background-position: 15px 15px; background-repeat: no-repeat;">
	<p style="font-weight: bold;">IMPORTANT UPGRADE NOTE UPGRADING TO v0.9</p>
	<ul style="padding-left: 20px;">
		<li><strong>Stylesheet</strong>: If you keep your current Bakery stylesheets, make sure you are changing the class names from mod<strong>e</strong>_ to mod_ (mod without &quot;<strong>e</strong>&quot;, eg. mod_bakery_anything_f).</li><br />
		<li><strong>Item options</strong>: Due to a new system handling the item options you lost all your item options. Use your database backup to restore the options and attributes to their former condition.</li>
	</ul>
</div>
';
}




// UPGRADE TO VERSION 0.9.6
// ************************

if ($bakery_settings['version'] < '0.96') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 0.9.6:</h3>';
	
	// Get SETTINGS table to see what needs to be changed
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	if ($settings = $settingstable->fetchRow()) {
	
		if (!array_key_exists('shop_state', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `shop_state` VARCHAR(2) NOT NULL AFTER `shop_country`")) {
					echo '<p class="good">Database field shop_state added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field shop_state exists, update not needed</p>'; }
		
		if (!array_key_exists('tax_by', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `tax_by` VARCHAR(10) NOT NULL AFTER `tax_included`")) {
					echo '<p class="good">Database field tax_by added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field tax_by exists, update not needed</p>'; }
	
		// Insert default settings into general settings table
		if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_general_settings` SET `tax_by` = 'country'")) {
			echo '<p class="good">Added default general settings successfully</p>';
		}
		else {
			echo '<p class="bad">'.mysql_error().'</p>';
		}
	}
}

	

	
// UPGRADE TO VERSION 0.9.7
// ************************

if ($bakery_settings['version'] < '0.97') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 0.9.7:</h3>';
	
	// Get SETTINGS table to see what needs to be changed
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	if ($settings = $settingstable->fetchRow()) {
	
		echo "<h4>Modifying table general_settings:</h4>";
		
		if (!array_key_exists('definable_field_0', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `definable_field_0` VARCHAR(50) NOT NULL AFTER `use_captcha`")) {
					echo '<p class="good">Database field definable_field_0 added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field definable_field_0 exists, update not needed</p>'; }

		if (!array_key_exists('definable_field_1', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `definable_field_1` VARCHAR(50) NOT NULL AFTER `definable_field_0`")) {
					echo '<p class="good">Database field definable_field_1 added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field definable_field_1 exists, update not needed</p>'; }
		
		if (!array_key_exists('definable_field_2', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `definable_field_2` VARCHAR(50) NOT NULL AFTER `definable_field_1`")) {
					echo '<p class="good">Database field definable_field_2 added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field definable_field_2 exists, update not needed</p>'; }
		
		if (!array_key_exists('stock_mode', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `stock_mode` VARCHAR(10) NOT NULL AFTER `definable_field_2`")) {
					echo '<p class="good">Database field stock_mode added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field stock_mode exists, update not needed</p>'; }
		
		if (!array_key_exists('stock_limit', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `stock_limit` INT(3) NOT NULL AFTER `stock_mode`")) {
					echo '<p class="good">Database field stock_limit added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field stock_limit exists, update not needed</p>'; }
	}
	
	// Change continue_url to a link not containing domain name nor page directory
	echo "<h4>Modifying table page_settings:</h4>";
	$settingstable = $database->query("SELECT section_id, continue_url FROM `".TABLE_PREFIX."mod_bakery_page_settings`");
	while ($settings = $settingstable->fetchRow()) {
		$section_id = $settings['section_id'];
		$continue_url = str_replace(array(WB_URL.PAGES_DIRECTORY, PAGE_EXTENSION), array("", ""), $settings['continue_url']);
		if ($database->query("UPDATE ".TABLE_PREFIX."mod_bakery_page_settings SET continue_url = '$continue_url' WHERE section_id = '$section_id'")) {
			echo '<p class="good">Changed continue_url of section_id='.$section_id.' successfully to a link not containing domain name nor page directory</p>';
		} else {
			echo '<p class="bad">'.mysql_error().'</p>';
			}
	}
	
	// Get ITEMS table to see what needs to be added
	echo "<h4>Modifying table items:</h4>";
	$itemstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_items`");
	if ($item = $itemstable->fetchRow()) {

		if (!array_key_exists('definable_field_0', $item)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` ADD `definable_field_0` VARCHAR(150) NOT NULL AFTER `tax_rate`")) {
					echo '<p class="good">Database field definable_field_0 added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field definable_field_0 exists, update not needed</p>'; }

		if (!array_key_exists('definable_field_1', $item)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` ADD `definable_field_1` VARCHAR(150) NOT NULL AFTER `definable_field_0`")) {
					echo '<p class="good">Database field definable_field_1 added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field definable_field_1 exists, update not needed</p>'; }
		
		if (!array_key_exists('definable_field_2', $item)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` ADD `definable_field_2` VARCHAR(150) NOT NULL AFTER `definable_field_1`")) {
					echo '<p class="good">Database field definable_field_2 added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field definable_field_2 exists, update not needed</p>'; }
		
	}
	
	// Change item link to a link not containing domain name nor page directory
	while ($item = $itemstable->fetchRow()) {
		$item_id = $item['item_id'];
		$link = str_replace(PAGES_DIRECTORY, "", $item['link']);
		if ($database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET link = '$link' WHERE item_id = '$item_id'")) {
			echo '<p class="good">Changed item link of item_id='.$item_id.' successfully to a link not containing domain name nor page directory</p>';
		} else {
			echo '<p class="bad">'.mysql_error().'</p>';
			}
	}
	
}	




// UPGRADE TO VERSION 1.1
// **********************

if ($bakery_settings['version'] < '1.1') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 1.1:</h3>';


	// Add new fields to the general settings table
	echo'<h4>Adding new fields to the general_settings table</h4>';
	
	// Get SETTINGS table to see what needs to be changed
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	if ($settings = $settingstable->fetchRow()) {

		if (!array_key_exists('display_settings', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `display_settings` ENUM('1','0') NOT NULL DEFAULT '1' AFTER `zip_location`")) {
					echo '<p class="good">Database field display_settings added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field display_settings exists, update not needed</p>'; }


		if (!array_key_exists('out_of_stock_orders', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `out_of_stock_orders` ENUM('1','0') NOT NULL DEFAULT '0' AFTER `stock_limit`")) {
					echo '<p class="good">Database field out_of_stock_orders added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field out_of_stock_orders exists, update not needed</p>'; }


		if (!array_key_exists('thousands_sep', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `thousands_sep` VARCHAR(1) NOT NULL DEFAULT '\'' AFTER `shop_currency`")) {
					echo '<p class="good">Database field thousands_sep added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field thousands_sep exists, update not needed</p>'; }


		if (!array_key_exists('dec_point', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `dec_point` VARCHAR(1) NOT NULL DEFAULT '.' AFTER `shop_currency`")) {
					echo '<p class="good">Database field dec_point added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field dec_point exists, update not needed</p>'; }


		if (!array_key_exists('skip_checkout', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `skip_checkout` ENUM('1','0') NOT NULL DEFAULT '0' AFTER `tax_included`")) {
					echo '<p class="good">Database field skip_checkout added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field skip_checkout exists, update not needed</p>'; }
	}


	// Add new payment methods table to the database
	echo'<h4>Adding new payment methods table to the database</h4>';
	
	// Create new PAYMENT METHODS table
	$mod_bakery = 'CREATE TABLE `'.TABLE_PREFIX.'mod_bakery_payment_methods` ( '
			. '`pm_id` INT(11) NOT NULL AUTO_INCREMENT ,'
			. '`active` INT(1) NOT NULL ,'
			. '`directory` VARCHAR(50) NOT NULL ,'
			. '`name` VARCHAR(50) NOT NULL ,'
			. '`version` VARCHAR(6) NOT NULL ,'
			. '`author` VARCHAR(50) NOT NULL ,'
			. '`requires` VARCHAR(6) NOT NULL ,'
			. '`field_1` VARCHAR(150) NOT NULL ,'
			. '`value_1` TEXT NOT NULL ,'
			. '`field_2` VARCHAR(150) NOT NULL ,'
			. '`value_2` TEXT NOT NULL ,'
			. '`field_3` VARCHAR(150) NOT NULL ,'
			. '`value_3` TEXT NOT NULL ,'
			. '`field_4` VARCHAR(150) NOT NULL ,'
			. '`value_4` TEXT NOT NULL ,'
			. '`field_5` VARCHAR(150) NOT NULL ,'
			. '`value_5` TEXT NOT NULL ,'
			. '`field_6` VARCHAR(150) NOT NULL ,'
			. '`value_6` TEXT NOT NULL ,'
			. '`cust_email_subject` TEXT NOT NULL ,'
			. '`cust_email_body` TEXT NOT NULL ,'
			. '`shop_email_subject` TEXT NOT NULL ,'
			. '`shop_email_body` TEXT NOT NULL ,'
			. 'PRIMARY KEY (`pm_id`)'
			. ' )';
	if ($database->query($mod_bakery)) {
		echo '<p class="good">Created new payment_methods table successfully</p>';
	}
	else {
	echo '<p class="bad">'.mysql_error().'</p>';
	}


	// Add all avaiable payment_methods to the db
	echo'<h4>Adding all avaiable payment_methods to the database</h4>';
	
	if (!file_exists(WB_PATH.'/modules/bakery/payment_methods/load.php')) {
		echo '<p class="bad">File load.php is missing. Cannot create new database table payment_methods nor move the payment method settings. Please add your payment settings manually!</p>';
	} else {
		// Include payment methods loading file
		include(WB_PATH.'/modules/bakery/payment_methods/load.php');

		// Get "old" general settings
		if ($settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`")) {
			$settings = $settingstable->fetchRow();
			extract($settings);
			$moved_successfully = true;

			// Loop through the payment methods and overwrite default values that have been inserted by load.php
			foreach ($load_payment_methods as $payment_method) {
				switch ($payment_method) {
					case 'advance':
						if ($database->query("UPDATE ".TABLE_PREFIX."mod_bakery_payment_methods SET cust_email_subject = '$email_subject_advance', cust_email_body = '$email_pay_advance' WHERE `directory` = 'advance'")) {
							echo '<p class="good">Moved advance payment method settings to the payment_methods table successfully</p>';
						} else {
							echo '<p class="bad">'.mysql_error().'</p>';
							$moved_successfully = false;
						}
					break;
					case 'invoice':
						if ($database->query("UPDATE ".TABLE_PREFIX."mod_bakery_payment_methods SET value_1 = '$bank_account', value_4 = '$invoice', cust_email_subject = '$email_subject_invoice', cust_email_body = '$email_invoice' WHERE `directory` = 'invoice'")) {
							echo '<p class="good">Moved invoice payment method settings to the payment_methods table successfully</p>';
						} else {
							echo '<p class="bad">'.mysql_error().'</p>';
							$moved_successfully = false;
						}
					break;
					case 'paypal':
					if ($database->query("UPDATE ".TABLE_PREFIX."mod_bakery_payment_methods SET value_1 = '$paypal_email', value_2 = '$paypal_page', cust_email_subject = '$email_subject_paypal', cust_email_body = '$email_paypal' WHERE `directory` = 'paypal'")) {
						echo '<p class="good">Moved PayPal payment method settings to the payment_methods table successfully</p>';
					} else {
						echo '<p class="bad">'.mysql_error().'</p>';
						$moved_successfully = false;
					}
					break;
				}
			}
		}
	}	


	// Delete all fields which have been moved to the payment_methods table
	echo'<h4>Deleting all fields which have been moved to the payment methods table</h4>';
	
	if ($moved_successfully) {
		if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` DROP `payment_method`, DROP `bank_account`, DROP `paypal_email`, DROP `paypal_page`, DROP `email_subject_advance`, DROP `email_pay_advance`, DROP `email_subject_paypal`, DROP `email_paypal`, DROP `email_subject_invoice`, DROP `email_invoice`, DROP `invoice`")) {
			echo '<p class="good">Deleted all fields of general_settings (that have been moved to the payment_methods table) successfully</p>';
		}
		else {
			echo '<p class="bad">'.mysql_error().'</p>';
		}
	} else {
		echo '<p class="bad">Did not drop general_settings fields since they have not been moved to the payment_methods table</p>';
	}


	// Modifying fields or adding new fields to the existing CUSTOMER table
	echo'<h4>Modifying fields and adding new fields to the customer table</h4>';

	// Get CUSTOMER table to see what needs to be created
	$customertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer`");
	$num_customers = $customertable->numRows();
	if ($num_customers == 0) {
		$database->query("INSERT INTO " .TABLE_PREFIX."mod_bakery_customer (order_id) VALUES ('0')");
	}
	$customertable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer`");
	$customer = $customertable->fetchRow();

	if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` CHANGE `submitted` `submitted` VARCHAR(20) NOT NULL DEFAULT 'no'")) {
		echo '<p class="good">Changed database field submitted to type VARCHAR(20) successfully</p>';
	}
	else {
		echo '<p class="bad">'.mysql_error().'</p>';
	}

	if (!array_key_exists('transaction_status', $customer)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `transaction_status` VARCHAR(10) NOT NULL DEFAULT 'none' AFTER `submitted`")) {
				echo '<p class="good">Database field transaction_status added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field transaction_status exists, update not needed</p>'; }

	if (!array_key_exists('transaction_id', $customer)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_customer` ADD `transaction_id` VARCHAR(50) NOT NULL DEFAULT 'none' AFTER `submitted`")) {
				echo '<p class="good">Database field transaction_id added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field transaction_id exists, update not needed</p>'; }


	// Change payment method abbreviations
	echo'<h4>Changing all payment method abbreviations to full length words</h4>';
	
	// Change payment method abbreviations to full length words
	$payment_methods = array("adv"=>"advance", "inv"=>"invoice", "pp"=>"paypal");
	foreach ($payment_methods as $abbr => $payment_method) {
		if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_customer` SET `submitted` = '$payment_method' WHERE `submitted` = '$abbr'")) {
			echo '<p class="good">Changed payment method abbreviation <strong>'.$abbr.'</strong> to full length word <strong>'.$payment_method.'</strong> successfully</p>';
		} else {
			echo '<p class="bad">'.mysql_error().'</p>';
		}
	}
}




// UPGRADE TO VERSION 1.3
// **********************

if ($bakery_settings['version'] < '1.3') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 1.3:</h3>';

	// Get ITEMS table to see what needs to be created
	$itemstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_items`");
	$items = $itemstable->fetchRow();
	
		if (!array_key_exists('main_image', $items)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` CHANGE `extension` `main_image` VARCHAR(50) NOT NULL")) {
					echo '<p class="good">Changed database field extension to main_image successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field main_image exists, update not needed</p>'; }
	

	// Get SETTINGS table to see what needs to be changed
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_page_settings`");
	if ($settings = $settingstable->fetchRow()) {
	
		if (!array_key_exists('lightbox2', $settings)){
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_page_settings` ADD `lightbox2` VARCHAR(10) NOT NULL DEFAULT 'detail' AFTER `resize`")) {
					echo '<p class="good">Database field lightbox2 added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field lightbox2 exists, update not needed</p>'; }

		if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_page_settings` SET `resize` = '100' WHERE resize = 0")) {
			echo '<p class="good">Changed thumbnail resize to 100x100px successfully</p>';
		} else {
			echo '<p class="bad">'.mysql_error().'</p>';
		}
	}


	// General upgrade note
	echo '
<div style="margin: 15px 0; padding: 10px 10px 10px 60px; text-align: left; color: red; border: solid 1px red; background-color: #FFDCD9; background-image: url('.WB_URL.'/modules/bakery/images/information.gif); background-position: 15px 15px; background-repeat: no-repeat;">
	<p style="font-weight: bold;">IMPORTANT UPGRADE NOTE UPGRADING TO v1.3</p>
	<ul style="padding-left: 20px;">
		<li><strong>Item images</strong>: Due to a new way how Bakery handles and stores images you will have to reupload <strong>ALL</strong> item images using the Bakery backend. Use your backup of the <code>/media/bakery</code> directory. Use speaking image file names since they are used for the image <code>&lt;alt&gt;<code> and </code>&lt;title&gt;</code> tag and shown as the Lightbox2 caption.</li><br />
		<li><strong>Item templates</strong>: Use the vars [THUMB], [THUMBS], [IMAGE] and [IMAGES] to display images. Depending on your page settings the images will be linked automatically to the detail page or overlay on the current page using Lightbox2. So there is no more need to link the image in your template.</li>
	</ul>
</div>
';
}




// UPGRADE TO VERSION 1.4.0
// ************************

if ($bakery_settings['version'] < '1.40') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 1.4.0:</h3>';

	// Change PAYMENT METHODS table
	if ($database->query("UPDATE `".TABLE_PREFIX."mod_bakery_payment_methods` SET `value_4` = `value_2`, `field_4` = `field_2`, `value_2` = '', `field_2` = '' WHERE `directory` = 'invoice' AND `version` = '0.1' LIMIT 1")) {
		echo '<p class="good">Changed database table payment methods successfully</p>';
	}
	else {
		echo '<p class="bad">'.mysql_error().'</p>';
	}


	// Get GENERAL SETTINGS table to see what needs to be changed
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	if ($settings = $settingstable->fetchRow()) {
	
		if (!array_key_exists('skip_cart', $settings)) {
				if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `skip_cart` ENUM('yes',  'no') NOT NULL DEFAULT 'no' AFTER `zip_location`")) {
					echo '<p class="good">Database field skip_cart added successfully</p>';
				} else { echo '<p class="bad">'.mysql_error().'</p>'; }
		} else { echo '<p class="ok">Database field skip_cart exists, update not needed</p>'; }
	}


	// Convert continue_url from string link to numeric page_id
	if ($database->query("UPDATE ".TABLE_PREFIX."mod_bakery_page_settings ps SET ps.continue_url = (SELECT p.page_id FROM ".TABLE_PREFIX."pages p WHERE ps.continue_url = p.link) WHERE LEFT(`continue_url`, 1) = '/'")) {
		echo '<p class="good">Converted continue_url from string link to numeric page_id successfully</p>';
	} else {
		echo '<p class="bad">'.mysql_error().'</p>';
	}


	// Replace all submit button names "cart" by new ones "view_cart" or "add_to_cart" at all page templates
	$display_warning = false;
	$replace_counter = 0;
	$query_page_settings = $database->query("SELECT section_id, header, item_loop, footer, item_header, item_footer FROM ".TABLE_PREFIX."mod_bakery_page_settings");
	if ($query_page_settings->numRows() > 0) {
		while ($page_settings = $query_page_settings->fetchRow()) {
			$page_settings = array_map('stripslashes', $page_settings);
			foreach ($page_settings as $template_name => $template_html) {
				if ($template_name != 'section_id' && !is_numeric($template_name)) {
					if ($template_name == 'header' || $template_name == 'footer') {
						$template_html = str_replace('name="cart"', 'name="view_cart"', $template_html);
						$updates[] = "$template_name = '$template_html'";
					} else {
						$template_html = str_replace('name="cart"', 'name="add_to_cart"', $template_html);
						$updates[] = "$template_name = '$template_html'";
					}
				}
			}
			$update_string = implode($updates,",");
			if ($database->query("UPDATE ".TABLE_PREFIX."mod_bakery_page_settings SET $update_string WHERE section_id = '{$page_settings['section_id']}'")) {
				if ($replace_counter == 0) {
					echo "<p class='good'>Replaced all submit buttons named &quot;cart&quot; by &quot;view_cart&quot; or &quot;add_to_cart&quot;&hellip;</p>";
				}
				echo "<p class='good'> &ndash; in all page templates with section id {$page_settings['section_id']} successfully</p>";
				$display_warning = true;
				$replace_counter++;
			} else {
				echo '<p class="bad">'.mysql_error().'</p>';
			}
		}
		// Upgrade note
		if ($display_warning) {
			echo '
<div style="margin: 15px 0; padding: 10px 10px 10px 60px; text-align: left; color: red; border: solid 1px red; background-color: #FFDCD9; background-image: url('.WB_URL.'/modules/bakery/images/information.gif); background-position: 15px 25px; background-repeat: no-repeat;">
	<p style="font-weight: bold;">IMPORTANT UPGRADE NOTES UPGRADING TO v1.4.0</p>
	<p style="font-weight: bold;">Due to the new delivery notes printing feature you have to modify your invoice template slightly.</p>
	<ol>
	  <li>Go to &quot;Payment Methods&quot; &gt; select &quot;Invoice&quot; &gt; &quot;Invoice Template&quot;.</li>
	  <li>Replace the placeholder [INVOICE_OR_DUNNING] by [TITLE].</li>
	  <li>Replace some lines of html code at the bottom of the invoice template by the appropriate ones. See example code at the <a href="http://www.bakery-shop.ch/#upgrade_note_140" target="_blank">Bakery website</a>.
	  <br />Use the help page at &quot;Payment Methods&quot; &gt; select &quot;Invoice&quot; &gt; &quot;Invoice Template&quot; &gt; &quot;Help&quot; to get information on the new placeholders [DISPLAY_INVOICE], [DISPLAY_DELIVERY_NOTE] and [DISPLAY_REMINDER].</li>
	</ol>
	<p style="font-weight: bold;">If your shop frontend is not working as expected please check all of your page templates manually.</p>
	<ol>
		<li>Select a Bakery page and go to &quot;Page Settings&quot; &gt; &quot;Layout Settings&quot;.</li>
		<li>Make sure all submit buttons formerly named <code>name=&quot;cart&quot;</code> have been replaced correctly by the upgrade script:
		<ul style="padding-left: 20px;">
			<li>Set <code>name=&quot;view_cart&quot;</code> for submit buttons that jump to the cart view.</li>
			<li>Set <code>name=&quot;add_to_cart&quot;</code> for submit buttons that add items to the cart.</li>
		</ul>
		</li>
		<li>Repeat for all other Bakery pages.</li>
	</ol>
</div>
';
		}
	}
}




// UPGRADE TO VERSION 1.5.1
// ************************

if ($bakery_settings['version'] < '1.51') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 1.5.1:</h3>';

	// Get GENERAL SETTINGS table to see what needs to be created
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	$settings = $settingstable->fetchRow();

	// Adding new fields to the existing SETTINGS table
	echo'<h4>Adding new field to the general_settings table</h4>';
	
	if (!array_key_exists('pages_directory', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `pages_directory` VARCHAR(20) NOT NULL DEFAULT 'bakery' AFTER `shop_email`")) {
				echo '<p class="good">Database field pages_directory added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field pages_directory exists, update not needed</p>'; }

}




// UPGRADE TO VERSION 1.5.5
// ************************

if ($bakery_settings['version'] < '1.55') {
	echo '
<div style="margin: 15px 0; padding: 10px 10px 10px 60px; text-align: left; color: red; border: solid 1px red; background-color: #FFDCD9; background-image: url('.WB_URL.'/modules/bakery/images/information.gif); background-position: 15px 25px; background-repeat: no-repeat;">
	<p style="font-weight: bold;">IMPORTANT UPGRADE NOTES UPGRADING TO BAKERY v1.5.5</p>
	<p style="font-weight: bold;">This upgrade note only concerns users of the DIRECTebanking.com / sofort&uuml;berweisung.de payment method.</p>
	<p style="padding: 5px; border: 1px solid red;">As a new  payment method security feature <strong>DIRECTebanking.com</strong> / <strong>sofort&uuml;berweisung.de</strong> now supports a notification password. This password is used to verify the HTTP response notifications. All users  of this payment method  have to add a notification password otherwise the payment method will not work properly any more. </p>
	<p>In order to set the notification password</p> follow the steps below:</p>
	<ol>
	  <li><a href="https://www.sofortueberweisung.de/payment/users/login" target="_blank">Log in</a> to your DIRECTebanking.com / sofort&uuml;berweisung.de account.</li>
	  <li>Go to &quot;My projects&quot; &gt; select a project &gt; &quot;Extended settings&quot; &gt; &quot;Passwords and hash algorithm&quot;</li>
	  <li>Set the notification password. Please note: As soon as the password is set, it can not be unset anymore.
	  <br />Copy or write down the generated notification password for later use.</li>
	  <li>Log in to the Bakery backend.</li>
      <li>Add the DIRECTebanking.com / sofort&uuml;berweisung.de  notification password at &quot;Payment Methods&quot; &gt; select &quot;DIRECTebanking.com / sofort&uuml;berweisung.de&quot; &gt; &quot;DIRECTebanking.com / sofort&uuml;berweisung.de Settings&quot;.</li>
	</ol>
</div>
';
}

// UPGRADE TO VERSION 1.6.0
// ************************

if ($bakery_settings['version'] <= '1.5.9') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 1.6.0:</h3>';

	// Get GENERAL SETTINGS table to see what needs to be created
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_general_settings`");
	$settings = $settingstable->fetchRow();

	// Adding new fields to the existing SETTINGS table
	echo'<h4>Adding new fields to the <em>general_settings</em> table</h4>';
	
	if (!array_key_exists('cancellation_url', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `cancellation_url` VARCHAR(255) NOT NULL DEFAULT '' AFTER `tac_url`")) {
				echo '<p class="good">Database field <em>cancellation_url</em> added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field <em>cancellation_url</em> exists, update not needed</p>'; }
	
	
	if (!array_key_exists('privacy_url', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_general_settings` ADD `privacy_url` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cancellation_url`")) {
				echo '<p class="good">Database field <em>privacy_url</em> added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field <em>privacy_url</em> exists, update not needed</p>'; }
	
	
	// Get PAGE SETTINGS table to see what needs to be created
	$settingstable = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_order`");
	$settings = $settingstable->fetchRow();

	// Adding new fields to the existing ORDER table
	echo'<h4>Adding new fields to the <em>order</em> table</h4>';
	
	if (!array_key_exists('textarea', $settings)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_order` ADD `textarea` VARCHAR(500) NOT NULL DEFAULT '' AFTER `attributes`")) {
				echo '<p class="good">Database field <em>textarea</em> added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field <em>textarea</em> exists, update not needed</p>'; }
}


// UPGRADE TO VERSION 1.7.1
// ************************

if ($bakery_settings['version'] <= '1.7.0') {

	// Titel: Upgrading to
	echo'<h3>Upgrading to version 1.7.1:</h3>';

	// Get ITEM table to see what needs to be created
	$db = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_items`");
	$items = $db->fetchRow();

	// Adding new fields to the existing ORDER table
	echo'<h4>Adding new fields to the <em>items</em> table</h4>';
	
	if (!array_key_exists('characteristics', $items)){
			if ($database->query("ALTER TABLE `".TABLE_PREFIX."mod_bakery_items` ADD `characteristics` TEXT NOT NULL DEFAULT '' AFTER `description`")) {
				echo '<p class="good">Database field <em>characteristics</em> added successfully</p>';
			} else { echo '<p class="bad">'.mysql_error().'</p>'; }
	} else { echo '<p class="ok">Database field <em>characteristics</em> exists, update not needed</p>'; }
}


// STOP FOR DEBUGGING - DISPLAY UPGRADE LOG
// ****************************************
?>

<div style="padding: 15px 10px; text-align: center; color: blue; border: solid 1px blue; background-color: #DCEAFE;">
	<p style="font-weight:bold;">Please check the log carefully. You might even want to copy or save it for later use.
	<br />Click the button to leave this page and finish your upgrade.</p>
</div>

