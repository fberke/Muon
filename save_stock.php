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

// Get category
if (isset($_POST['cat'])) {
	$category = is_numeric($_POST['cat']) ? $_POST['cat'] : '';
} else {
	$category = '';
}

// Loop through the items... 
foreach ($_POST['stock'] as $item_id => $stock) {
	$stock = $admin->add_slashes(strip_tags($stock));
	$active = isset($_POST['active'][$item_id]) ? 1 : 0;
	// ...and update items
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET stock = '$stock', active = '$active' WHERE item_id = '$item_id'");
}

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/stock.php?page_id='.$page_id.'&cat='.$category);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/bakery/stock.php?page_id='.$page_id.'&cat='.$category);
}

// Print admin footer
$admin->print_footer();


?>