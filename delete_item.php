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
 
// Get id
if (!isset($_GET['item_id']) OR !is_numeric($_GET['item_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$item_id = $_GET['item_id'];
}

// Include WB admin wrapper script and WB functions
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');
require_once(WB_PATH.'/framework/functions.php');

// Get item details
$query_details = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_items WHERE item_id = '$item_id'");
if ($query_details->numRows() > 0) {
	$get_details = $query_details->fetchRow();
} else {
	$admin->print_error($TEXT['NOT_FOUND'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Unlink item access file
if (is_writable(WB_PATH.PAGES_DIRECTORY.$get_details['link'].PAGE_EXTENSION)) {
	unlink(WB_PATH.PAGES_DIRECTORY.$get_details['link'].PAGE_EXTENSION);
}

// Delete any images if they exists
$image = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id;
$thumb = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id;
if (is_dir($image)) { rm_full_dir($image); }
if (is_dir($thumb)) { rm_full_dir($thumb); }

// Delete item
$database->query("DELETE FROM ".TABLE_PREFIX."mod_bakery_items WHERE item_id = '$item_id' LIMIT 1");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_bakery_item_attributes WHERE item_id = '$item_id'");

// Clean up ordering
require(WB_PATH.'/framework/class.order.php');
$order = new order(TABLE_PREFIX.'mod_bakery_items', 'position', 'item_id', 'section_id');
$order->clean($section_id); 

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/modify_post.php?page_id='.$page_id.'&item_id='.$item_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>