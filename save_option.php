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


if ($_POST['option_name'] != "") {
	// Insert new option name into db
	if ($_POST['option_id'] == "") {
		$option_name = $admin->add_slashes(strip_tags($_POST['option_name']));
		$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_options (option_name) VALUES ('$option_name')");
	}
	// Update option name
	else {
		$option_id = $admin->add_slashes(strip_tags($_POST['option_id']));
		$option_name = $admin->add_slashes(strip_tags($_POST['option_name']));
		$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_options SET option_name = '$option_name' WHERE option_id = '$option_id'");
	}
} else {
	$admin->print_error($MESSAGE['MEDIA']['BLANK_NAME'], WB_URL.'/modules/bakery/modify_options.php?page_id='.$page_id);
}


// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/modify_options.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/bakery/modify_options.php?page_id='.$page_id);
}


// Print admin footer
$admin->print_footer();


?>