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
 

// Get module pages directory from general setting table
$query_general_settings = $database->query("SELECT pages_directory FROM ".TABLE_PREFIX."mod_bakery_general_settings");
$general_settings       = $query_general_settings->fetchRow();
$module_pages_directory = '/'.$general_settings['pages_directory'];

// Delete
$database->query("DELETE FROM ".TABLE_PREFIX."search WHERE name = 'module' AND value = 'bakery'");
$database->query("DELETE FROM ".TABLE_PREFIX."search WHERE extra = 'bakery'");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_items");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_options");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_attributes");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_item_attributes");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_customer");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_order");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_general_settings");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_page_settings");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_bakery_payment_methods");

require_once(WB_PATH.'/framework/functions.php');
$directory = WB_PATH.PAGES_DIRECTORY.$module_pages_directory;
if (is_dir($directory)) {
	rm_full_dir($directory);
}

$directory = WB_PATH.MEDIA_DIRECTORY.'/bakery';
if (is_dir($directory)) {
	rm_full_dir($directory);
}

?>