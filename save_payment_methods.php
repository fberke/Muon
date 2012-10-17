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
$update_payment_method = $admin->add_slashes(strip_tags($_POST['update_payment_method']));
$modify_payment_method = $admin->add_slashes(strip_tags($_POST['modify_payment_method']));
$reload = $_POST['reload'] == 'true' ? true : false;
$skip_checkout = isset($_POST['skip_checkout']) ? 1 : 0;


// Update payment methods 'active'
foreach ($_POST['all_payment_methods'] as $pm_id) {
	if (is_numeric($pm_id)) {
		$active = isset($_POST['payment_methods'][$pm_id]) ? 1 : 0;
		$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_payment_methods SET active = '$active' WHERE pm_id = '$pm_id'");
	}
}


// Update 'skip checkout' in general settings table
$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_general_settings SET skip_checkout = '$skip_checkout'");


// Write fields into db
foreach ($_POST['update'] as $field => $value) {
	$field = $admin->add_slashes(strip_tags($field));
	$value = ($update_payment_method == "invoice" && $field == "value_4") ? $admin->add_slashes($value) : $admin->add_slashes(strip_tags($value));
	$updates[] = "$field = '$value'";
}
$update_string = implode($updates,", ");

// Update payment methods
$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_payment_methods SET $update_string WHERE directory = '$update_payment_method'");

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	// If a payment method has been selected go back to the payment method page
	if ($reload) {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/bakery/modify_payment_methods.php?page_id='.$page_id.'&section_id='.$section_id.'&payment_method='.$modify_payment_method);
	} else {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
}

// Print admin footer
$admin->print_footer();

?>
