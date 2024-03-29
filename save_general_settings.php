<?php



require('../../config.php');


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
 
// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');


// Remove any tags and add slashes
$reload = $_POST['reload'] == 'true' ? true : false;

$shop_name = $admin->add_slashes(strip_tags($_POST['shop_name']));
$shop_email = $admin->add_slashes(strip_tags($_POST['shop_email']));
$pages_directory = $admin->add_slashes(strip_tags($_POST['pages_directory']));
$tac_url = $admin->add_slashes(strip_tags($_POST['tac_url']));
$cancellation_url = $admin->add_slashes(strip_tags($_POST['cancellation_url']));
$privacy_url = $admin->add_slashes(strip_tags($_POST['privacy_url']));
$shop_country = $admin->add_slashes(strip_tags($_POST['shop_country']));
$shop_state = isset($_POST['shop_state']) ? $admin->add_slashes(strip_tags($_POST['shop_state'])) : "";
$shipping_form = $admin->add_slashes(strip_tags($_POST['shipping_form']));
$state_field = isset($_POST['state_field']) ? "show" : "hide";
$zip_location = isset($_POST['zip_location']) ? "end" : "inside";
$skip_checkout = isset($_POST['skip_checkout']) ? 1 : 0;
$display_settings = isset($_POST['display_settings']) ? 1 : 0;
$skip_cart = isset($_POST['skip_cart']) ? "yes" : "no";
$out_of_stock_orders = isset($_POST['out_of_stock_orders']) ? 1 : 0;
$use_captcha = isset($_POST['use_captcha']) ? "yes" : "no";

$definable_field_0 = $admin->add_slashes(strip_tags($_POST['definable_field_0']));
$definable_field_1 = $admin->add_slashes(strip_tags($_POST['definable_field_1']));
$definable_field_2 = $admin->add_slashes(strip_tags($_POST['definable_field_2']));
$stock_mode = $admin->add_slashes(strip_tags($_POST['stock_mode']));
$stock_limit = $admin->add_slashes(strip_tags($_POST['stock_limit']));

$shop_currency = $admin->add_slashes(strip_tags($_POST['shop_currency']));
$dec_point = $admin->add_slashes(strip_tags($_POST['dec_point']));
$thousands_sep = $admin->add_slashes(strip_tags($_POST['thousands_sep']));
$tax_rate = $admin->add_slashes(strip_tags($_POST['tax_rate']));
$tax_rate1 = $admin->add_slashes(strip_tags($_POST['tax_rate1']));
$tax_rate2 = $admin->add_slashes(strip_tags($_POST['tax_rate2']));
$tax_included = isset($_POST['tax_included']) ? "included" : "excluded";
$tax_by = $admin->add_slashes(strip_tags($_POST['tax_by']));

$tax_rate_shipping = $admin->add_slashes(strip_tags($_POST['tax_rate_shipping']));
$free_shipping = $admin->add_slashes(strip_tags($_POST['free_shipping']));
$free_shipping_msg = isset($_POST['free_shipping_msg']) ? "show" : "hide";
$shipping_method = $admin->add_slashes(strip_tags($_POST['shipping_method']));
$shipping_domestic = $admin->add_slashes(strip_tags($_POST['shipping_domestic']));
$shipping_abroad = $admin->add_slashes(strip_tags($_POST['shipping_abroad']));
$shipping_zone = $admin->add_slashes(strip_tags($_POST['shipping_zone']));
$zone_countries = isset($_POST['zone_countries']) ? implode(",", $_POST['zone_countries']) : "";


// Clean out protocol names if added to the shop name
// to prevent problems with the php mail() function
$shop_name = str_replace("http://",  "", $shop_name);
$shop_name = str_replace("https://", "", $shop_name);


// If no state file exists for the selected country...
if (!file_exists(WB_PATH.'/modules/bakery/languages/states/'.$shop_country.'.php')) {
	// ...set shop state to blank
	$shop_state = "";
	// ...change tax by from state to country
	if ($tax_by == "state") $tax_by = "country";
}


// Get current general settings
$query_general_settings = $database->query("SELECT pages_directory, tax_rate, tax_rate1, tax_rate2 FROM ".TABLE_PREFIX."mod_bakery_general_settings");
$general_settings = $query_general_settings->fetchRow();


// If a tax rate setting has changed => update items tax rate 
if ($general_settings['tax_rate'] != $tax_rate) {
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET tax_rate = $tax_rate WHERE tax_rate = {$general_settings['tax_rate']}");
}
if ($general_settings['tax_rate1'] != $tax_rate1) {
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET tax_rate = $tax_rate1 WHERE tax_rate = {$general_settings['tax_rate1']}");
}
if ($general_settings['tax_rate2'] != $tax_rate2) {
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET tax_rate = $tax_rate2 WHERE tax_rate = {$general_settings['tax_rate2']}");
}


// Rename Bakery pages directory

// Old and new directory pathes
$old_pages_dir = WB_PATH.PAGES_DIRECTORY.'/'.$general_settings['pages_directory'].'/';
$new_pages_dir = WB_PATH.PAGES_DIRECTORY.'/'.$pages_directory.'/';

// Rename if the pages directory has changed
if ($general_settings['pages_directory'] != $pages_directory) {
	// Check if the pages directory name does not exist yet
	if (is_dir($new_pages_dir)) {
		$admin->print_error($MESSAGE['MEDIA']['DIR_EXISTS'], WB_URL.'/modules/bakery/modify_general_settings.php?page_id='.$page_id.'&section_id='.$section_id);
	}
	// Rename directory
	if (rename($old_pages_dir, $new_pages_dir)) {
		// Update item links
		$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET link = REPLACE(link, '/{$general_settings['pages_directory']}/', '/$pages_directory/')");
	}
	else {
		$admin->print_error($MESSAGE['MEDIA']['CANNOT_RENAME'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}	
}


// Update general settings
$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_general_settings SET shop_name = '$shop_name', shop_email = '$shop_email', pages_directory = '$pages_directory', tac_url = '$tac_url', cancellation_url = '$cancellation_url', privacy_url = '$privacy_url', shop_country = '$shop_country', shop_state = '$shop_state', shipping_form = '$shipping_form', state_field = '$state_field', zip_location = '$zip_location', skip_checkout = '$skip_checkout', display_settings = '$display_settings', skip_cart = '$skip_cart', out_of_stock_orders = '$out_of_stock_orders', use_captcha = '$use_captcha', definable_field_0 = '$definable_field_0', definable_field_1 = '$definable_field_1', definable_field_2 = '$definable_field_2', stock_mode = '$stock_mode', stock_limit = '$stock_limit', shop_currency = '$shop_currency', dec_point = '$dec_point', thousands_sep = '$thousands_sep', tax_rate = '$tax_rate', tax_rate1 = '$tax_rate1', tax_rate2 = '$tax_rate2', tax_included = '$tax_included', tax_by = '$tax_by', tax_rate_shipping = '$tax_rate_shipping', free_shipping = '$free_shipping', free_shipping_msg = '$free_shipping_msg', shipping_method = '$shipping_method', shipping_domestic = '$shipping_domestic', shipping_abroad = '$shipping_abroad', shipping_zone = '$shipping_zone', zone_countries = '$zone_countries'");

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	// If a country has been selected go back to the general settings page
	if ($reload) {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/bakery/modify_general_settings.php?page_id='.$page_id.'&section_id='.$section_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
}

// Print admin footer
$admin->print_footer();

?>