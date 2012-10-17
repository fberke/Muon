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
 

// Include WB functions
require_once(WB_PATH.'/framework/functions.php');

// Delete item access file, images and thumbs associated with the section
$query_items = $database->query("SELECT item_id, link FROM ".TABLE_PREFIX."mod_bakery_items WHERE section_id = '$section_id'");
if ($query_items->numRows() > 0) {
	while ($item = $query_items->fetchRow()) {
		// Delete item access file
		if (is_writable(WB_PATH.PAGES_DIRECTORY.$item['link'].PAGE_EXTENSION)) { unlink(WB_PATH.PAGES_DIRECTORY.$item['link'].PAGE_EXTENSION); }
		// Delete any images if they exists
		$image = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item['item_id'];
		$thumb = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item['item_id'];
		if (is_dir($image)) { rm_full_dir($image); }
		if (is_dir($thumb)) { rm_full_dir($thumb); }
		// Delete item attributes in db
		$database->query("DELETE FROM ".TABLE_PREFIX."mod_bakery_item_attributes WHERE item_id = '{$item['item_id']}'");
	}
}

// Delete items and page settings in db
$database->query("DELETE FROM ".TABLE_PREFIX."mod_bakery_items WHERE section_id = '$section_id'");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_bakery_page_settings WHERE section_id = '$section_id'");

?>