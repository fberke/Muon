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


require('resize_img.php');


// Look for language file
if (LANGUAGE_LOADED) {
    include(WB_PATH.'/modules/bakery/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
        include(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
    }
}

// Get item id
if (!isset($_POST['item_id']) OR !is_numeric($_POST['item_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$id = $_POST['item_id'];
	$item_id = $id;
}

// Create new order object
require(WB_PATH.'/framework/class.order.php');
$order = new order(TABLE_PREFIX.'mod_bakery_items', 'position', 'item_id', 'section_id');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Remove any tags and add slashes
$old_link = strip_tags($admin->get_post('link'));
$old_section_id = strip_tags($admin->get_post('section_id'));
$new_section_id = strip_tags($admin->get_post('new_section_id'));
$action = strip_tags($admin->get_post('action'));

$title = $admin->add_slashes(strip_tags($admin->get_post('title')));
$sku = $admin->add_slashes(strip_tags($admin->get_post('sku')));
$stock = $admin->add_slashes(strip_tags($admin->get_post('stock')));
$price = $admin->add_slashes(strip_tags($admin->get_post('price')));
$shipping = $admin->add_slashes(strip_tags($admin->get_post('shipping')));
$tax_rate = $admin->add_slashes(strip_tags($admin->get_post('tax_rate')));
$definable_field_0 = $admin->add_slashes(strip_tags($admin->get_post('definable_field_0')));
$definable_field_1 = $admin->add_slashes(strip_tags($admin->get_post('definable_field_1')));
$definable_field_2 = $admin->add_slashes(strip_tags($admin->get_post('definable_field_2')));
$main_image = $admin->add_slashes(strip_tags($admin->get_post('main_image')));
$description = $admin->add_slashes(strip_tags($admin->get_post('description')));
$characteristics = $admin->add_slashes(strip_tags($admin->get_post('characteristics')));
$full_desc = $admin->add_slashes($admin->get_post('full_desc'));
$imgresize = strip_tags($admin->get_post('imgresize'));
$quality = strip_tags($admin->get_post('quality'));
$maxheight = strip_tags($admin->get_post('maxheight'));
$maxwidth = strip_tags($admin->get_post('maxwidth'));
$active = strip_tags($admin->get_post('active'));

$attribute_id = $admin->add_slashes(strip_tags($admin->get_post('attribute_id')));
$ia_operator = $admin->add_slashes(strip_tags($admin->get_post('ia_operator')));
$ia_price = $admin->add_slashes(strip_tags($admin->get_post('ia_price')));
$db_action = strip_tags($admin->get_post('db_action'));

// Validate the title field
if ($admin->get_post('title') == '') {
	// Put item data into the session var to prepopulate the text fields after the error message
	$_SESSION['bakery']['item']['title'] = $title;
	$_SESSION['bakery']['item']['sku'] = $sku;
	$_SESSION['bakery']['item']['stock'] = $stock;
	$_SESSION['bakery']['item']['price'] = $price;
	$_SESSION['bakery']['item']['shipping'] = $shipping;
	$_SESSION['bakery']['item']['tax_rate'] = $tax_rate;
	$_SESSION['bakery']['item']['definable_field_0'] = $definable_field_0;
	$_SESSION['bakery']['item']['definable_field_1'] = $definable_field_1;
	$_SESSION['bakery']['item']['definable_field_2'] = $definable_field_2;
	$_SESSION['bakery']['item']['main_image'] = $main_image;
	$_SESSION['bakery']['item']['description'] = $description;
	$_SESSION['bakery']['item']['characteristics'] = $characteristics;
	$_SESSION['bakery']['item']['full_desc'] = $full_desc;
	$_SESSION['bakery']['item']['imgresize'] = $imgresize;
	$_SESSION['bakery']['item']['quality'] = $quality;
	$_SESSION['bakery']['item']['maxheight'] = $maxheight;
	$_SESSION['bakery']['item']['maxwidth'] = $maxwidth;
	$_SESSION['bakery']['item']['active'] = $active;
	$_SESSION['bakery']['item']['new_section_id'] = $new_section_id;
	$_SESSION['bakery']['item']['action'] = $action;
	// Show error message and go back
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id);
}



// MOVE ITEM TO ANOTHER BAKERY SECTION/PAGE

$moved = false;
if ($old_section_id != $new_section_id && $action == 'move') {
	// Get new page and section ids
	$query_sections = $database->query("SELECT page_id FROM ".TABLE_PREFIX."sections WHERE section_id = '$new_section_id'");
	$sections = $query_sections->fetchRow();
	$page_id = $sections['page_id'];
	$section_id = $new_section_id;
	// Get new order position
	$position = $order->get_new($section_id);
	$moved = true;
}



// ACCESS FILE

// Get module pages directory from general setting table
$query_general_settings = $database->query("SELECT pages_directory FROM ".TABLE_PREFIX."mod_bakery_general_settings");
$general_settings       = $query_general_settings->fetchRow();
$module_pages_directory = '/'.$general_settings['pages_directory'].'/';

// Include WB functions file
require(WB_PATH.'/framework/functions.php');

// Work-out what the link should be
$item_link = $module_pages_directory.page_filename($title).PAGE_SPACER.$item_id;
// Replace triple page spacer by one page spacer
$item_link = str_replace(PAGE_SPACER.PAGE_SPACER.PAGE_SPACER, PAGE_SPACER, $item_link);

// Make sure the item link is set and exists
// Make new item access files dir
make_dir(WB_PATH.PAGES_DIRECTORY.$module_pages_directory);
if (!is_writable(WB_PATH.PAGES_DIRECTORY.$module_pages_directory)) {
	$admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE']);
} elseif ($old_link != $item_link OR !file_exists(WB_PATH.PAGES_DIRECTORY.$item_link.PAGE_EXTENSION) OR $moved) {
	// We need to create a new file
	// First, delete old file if it exists
	if (file_exists(WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION) && $action != 'duplicate') {
		unlink(WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION);
	}
	// Specify the filename
	$filename = WB_PATH.PAGES_DIRECTORY.$item_link.PAGE_EXTENSION;
	// The depth of the page directory in the directory hierarchy
	// 'PAGES_DIRECTORY' is at depth 1
	$pages_dir_depth = count(explode('/',PAGES_DIRECTORY))-1;
	// Work-out how many ../'s we need to get to the index page
	$index_location = '../';
	for ($i = 0; $i < $pages_dir_depth; $i++) {
		$index_location .= '../';
	}
	// Write to the filename
	$content = ''.
'<?php
$page_id = '.$page_id.';
$section_id = '.$section_id.';
$item_id = '.$item_id.';
define("ITEM_ID", $item_id);
require("'.$index_location.'config.php");
require(WB_PATH."/index.php");
?>';
	$handle = fopen($filename, 'w');
	fwrite($handle, $content);
	fclose($handle);
	change_mode($filename);
}



// IMAGE AND THUMBNAIL

// Make sure the target directories exist
// Set array of all directories needed
$directories = array(
	'',
	'/images',
	'/thumbs',
	'/images/item'.$item_id,
	'/thumbs/item'.$item_id
);

// Try and make the directories
foreach ($directories as $directory) {
	$directory_path = WB_PATH.MEDIA_DIRECTORY.'/bakery'.$directory;
	make_dir($directory_path);

	// Add index.php files if not yet existing
	if (!is_file($directory_path.'/index.php')) {
		$content = ''.
"<?php

header('Location: ../');

?>";
		$handle = fopen($directory_path.'/index.php', 'w');
		fwrite($handle, $content);
		fclose($handle);
		change_mode($directory_path.'/index.php', 'file');
	}
}

// Delete image if requested
if (isset($_POST['delete_image']) AND $_POST['delete_image'] != '') {
	foreach ($_POST['delete_image'] as $img_file) {
		// Thumbs use .jpg extension only
		$thumb_file = str_replace (".png", ".jpg", $img_file);
		// Try unlinking image and thumb
		if (file_exists(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$img_file)) {
			unlink(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$img_file);
		}
		if (file_exists(WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id.'/'.$thumb_file)) {
			unlink(WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id.'/'.$thumb_file);
		}
		// If deleted item is main image set main image to empty string
		if ($img_file == $main_image) {
			$main_image = '';
		}
	}
}

// Add uploaded images
$upload_error = "";
$file_type_error = false;
$num_images = count($_FILES['image']['name']);
// Loop through the uploaded image(s)
for ($i = 0; $i < $num_images; $i++) {
	if (isset($_FILES['image']['tmp_name'][$i]) AND $_FILES['image']['tmp_name'][$i] != '') {

		// Get real filename and set new filename
		$file = $_FILES['image']['name'][$i];
		$path_parts = pathinfo($file);
		$filename = $path_parts['basename'];
		$fileext = $path_parts['extension'];
		$filename = str_replace(".".$fileext, "", $filename);  // Filename without extension
		$filename = str_replace(" ", "_", $filename);          // Replace spaces by underscores
		$fileext = strtolower($fileext);
		
		// Path to the new file
		$new_file = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$filename.'.'.$fileext;

		// Make sure the image is a jpg or png file
		if (!($fileext == "jpg" || $fileext == "jpeg" || $fileext == "png")) {
			$file_type_error = true;
			continue;
		}
		// Check for invalide chars in filename
		if (!preg_match('#^[a-zA-Z0-9._-]*$#', $filename)) {
			$errors[] = $MOD_BAKERY['ERR_INVALID_FILE_NAME'].": ".htmlspecialchars($filename.'.'.$fileext);
			continue;
		}
		// Check if filename already exists
		if (file_exists($new_file)) {
			$errors[] = $MESSAGE['MEDIA']['FILE_EXISTS'].": ".htmlspecialchars($filename.'.'.$fileext);
			continue;
		}

		// Upload image
		move_uploaded_file($_FILES['image']['tmp_name'][$i], $new_file);
		change_mode($new_file);

		// Check if we need to create a thumb
		$query_settings = $database->query("SELECT resize FROM ".TABLE_PREFIX."mod_bakery_page_settings WHERE section_id = '$section_id'");
		$fetch_settings = $query_settings->fetchRow();
		$resize = $fetch_settings['resize'];
		if ($resize != 0) {
		
			// Thumbnail destination
			$thumb_destination = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id.'/'.$filename.'.jpg';
			
			// Check thumbnail type
			if (!($fileext == "png")) {
				make_thumb($new_file, $thumb_destination, $resize);
			} else {
				resizePNG($new_file, $thumb_destination, $resize, $resize);
			}
			change_mode($thumb_destination);
		}
	
	
		// Check if we need to resize the image
		if ($imgresize == "yes" && file_exists($new_file)) {
	
			// Image destination
			$img_destination = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$filename.'.jpg';

			// Check image type
			if (!($fileext == "png")) {
				resizeJPEG($new_file, $maxwidth, $maxheight, $quality);
			} else {
				if (resizePNG($new_file, $img_destination, $maxwidth, $maxheight)) {
					// Try unlinking png image not used any more
					if (file_exists(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$filename.'.png')) {
						unlink(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$filename.'.png');
					}
				}
			}
			change_mode($img_destination);
			
			// After resizing change file extension to .jpg for use with main image
			$fileext = 'jpg';
		}

		// If requested set uploaded file as main image
		if ($i == 0 && $main_image == 'upload') {
			$main_image = $filename.'.'.$fileext;
		}
	}
}

// If needed display upload error messages and return
if ($file_type_error || (isset($errors) && count($errors) > 0)) {
	if ($file_type_error) {
		$upload_error = $MESSAGE['GENERIC']['FILE_TYPES'].' .jpg / .jpeg / .png<br />';
	}
	if (isset($errors) && count($errors) > 0) {
		$upload_error .= implode("<br />", $errors);
	}
	$admin->print_error($upload_error, WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id);
}



// UPDATE DATABASE

// Either insert or update ITEM ATTRIBUT...
if (isset($_POST['save_attribute']) AND $_POST['save_attribute'] != '') {

	// Get option_id from the attributes table
	$query_attributes = $database->query("SELECT option_id FROM ".TABLE_PREFIX."mod_bakery_attributes WHERE attribute_id = '$attribute_id'");
	$attribute = $query_attributes->fetchRow();
	$option_id = stripslashes($attribute['option_id']);
	
	// Insert new item attribute
	if (isset($_POST['attribute_id'])) {
		if ($db_action == "insert") {
			$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_item_attributes (item_id, option_id, attribute_id, price, operator) VALUES ('$item_id', '$option_id', '$attribute_id', '$ia_price', '$ia_operator')");
		}
		// Update item attribute
		else {
			$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_item_attributes SET option_id = '$option_id', `price` = '$ia_price', `operator` = '$ia_operator' WHERE item_id = '$item_id' AND attribute_id = '$attribute_id'");
		}
	}
	
	// Check if there is a db error, otherwise say successful
	if ($database->is_error()) {
		$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id.'#options');
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id.'#options');
	}
}


// ... or update ITEM DATA
else {
	// Only update if position is set and has been changed
	$query_position = isset($position) ? " `position` = '$position'," : "";
	
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET
		`section_id` = '$section_id',
		`page_id` = '$page_id',
		`title` = '$title',
		`link` = '$item_link',
		`sku` = '$sku',
		`stock` = '$stock',
		`price` = '$price',
		`shipping` = '$shipping',
		`tax_rate` = '$tax_rate',
		`definable_field_0` = '$definable_field_0',
		`definable_field_1` = '$definable_field_1',
		`definable_field_2` = '$definable_field_2',
		`main_image` = '$main_image',
		`description` = '$description',
		`characteristics` = '$characteristics',
		`full_desc` = '$full_desc',
		`active` = '$active',
		$query_position
		`modified_when` = '".@mktime()."',
		`modified_by` = '".$admin->get_user_id()."
		' WHERE item_id = '$item_id'");

	// Check if there is a db error, otherwise say successful
	if ($database->is_error()) {
		$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id);
	} elseif ($action != 'duplicate') {

		// Different targets depending on the save action
		if (isset($_POST['save_and_return']) AND $_POST['save_and_return'] != '') {
			$return_url = WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id;
		}
		elseif (isset($_POST['save_and_return_to_images']) AND $_POST['save_and_return_to_images'] != '') {
			$return_url = WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id.'#images';
		}
		else {
			$return_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
		}
		// Print success message and return
		$admin->print_success($TEXT['SUCCESS'], $return_url);
	}

	// Clean up item ordering of former section id
	$order->clean($old_section_id); 
}






// DUPLICATE ITEM
// **************

if ($action == 'duplicate') {


	// DUPLICATE ITEM
	
	// Get new page and section ids
	if ($old_section_id != $new_section_id) {
		$query_sections = $database->query("SELECT page_id FROM ".TABLE_PREFIX."sections WHERE section_id = '$new_section_id'");
		$sections = $query_sections->fetchRow();
		$page_id = $sections['page_id'];
		$section_id = $new_section_id;
	}	
	// Get new order position
	$position = $order->get_new($section_id);
	// Insert new row into database
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_items (section_id, page_id, position) VALUES ('$section_id', '$page_id', '$position')");
	// Get the id
	$orig_item_id = $item_id;
	$item_id = $database->get_one("SELECT LAST_INSERT_ID()");



	// ACCESS FILE

	// Work-out what the link should be
	$item_link = $module_pages_directory.page_filename($title).PAGE_SPACER.$item_id;
	// Replace triple page spacer by one page spacer
	$item_link = str_replace(PAGE_SPACER.PAGE_SPACER.PAGE_SPACER, PAGE_SPACER, $item_link);
	
	// Make sure the item link is set and exists
	// Make new item access files dir
	if (!is_writable(WB_PATH.PAGES_DIRECTORY.$module_pages_directory)) {
		$admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE']);
	} else {
		// We need to create a new file
		// Specify the filename
		$filename = WB_PATH.PAGES_DIRECTORY.$item_link.PAGE_EXTENSION;
		// The depth of the page directory in the directory hierarchy
		// '/pages' is at depth 1
		$pages_dir_depth = count(explode('/',PAGES_DIRECTORY))-1;
		// Work-out how many ../'s we need to get to the index page
		$index_location = '../';
		for ($i = 0; $i < $pages_dir_depth; $i++) {
			$index_location .= '../';
		}
		// Write to the filename
		$content = ''.
'<?php
$page_id = '.$page_id.';
$section_id = '.$section_id.';
$item_id = '.$item_id.';
define("ITEM_ID", $item_id);
require("'.$index_location.'config.php");
require(WB_PATH."/index.php");
?>';
		$handle = fopen($filename, 'w');
		fwrite($handle, $content);
		fclose($handle);
		change_mode($filename);
	}



	// IMAGE AND THUMBNAIL
	
	// Prepare pathes to the source image and thumb directories
	$img_source_dir = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$orig_item_id;
	$thumb_source_dir = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$orig_item_id;

	// Make sure the target directories exist
	make_dir(WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id);
	make_dir(WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id);
		
	// Check if the image and thumb source directories exist
	if (is_dir($img_source_dir) && is_dir($thumb_source_dir)) {
		// Open the image directory then loop through its contents
		$dir = dir($img_source_dir);
		while (false !== $image_file = $dir->read()) {
			// Skip index file and pointers
			if (strpos($image_file, '.php') !== false || substr($image_file, 0, 1) == ".") {
				continue;
			}
			// Thumbs use .jpg extension only
			$thumb_file = str_replace (".png", ".jpg", $image_file);

			// Pathes to the image/thumb source and destination respectively
			$img_source = $img_source_dir.'/'.$image_file;
			$img_destination = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.$item_id.'/'.$image_file;
			$thumb_source = $thumb_source_dir.'/'.$thumb_file;
			$thumb_destination = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.$item_id.'/'.$thumb_file;

			// Try duplicating image and thumb
			if (file_exists($img_source)) {
				if (copy($img_source, $img_destination)) {
					change_mode($img_destination);
				}
			}
			if (file_exists($thumb_source)) {
				copy($thumb_source, $thumb_destination);
				change_mode($thumb_destination);
			}
		}
	}



	// UPDATE DATABASE

	// First get item attributes of the original item id
	$query_item_attributes = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_item_attributes WHERE item_id = '$orig_item_id'");
	if ($query_item_attributes->numRows() > 0) {
		while ($ia = $query_item_attributes->fetchRow()) {  // ia = item_attributes
			// Insert duplicated item attributes
			$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_item_attributes (item_id, option_id, attribute_id, price, operator) VALUES ('$item_id', '{$ia['option_id']}', '{$ia['attribute_id']}', '{$ia['price']}', '{$ia['operator']}')");
		}
	}
	
	// Update duplicated item data
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET section_id = '$section_id', page_id = '$page_id', title = '$title', link = '$item_link', `sku` = '$sku', `stock` = '$stock', `price` = '$price', `shipping` = '$shipping', `tax_rate` = '$tax_rate', `definable_field_0` = '$definable_field_0', `definable_field_1` = '$definable_field_1', `definable_field_2` = '$definable_field_2', `main_image` = '$main_image', `description` = '$description', `full_desc` = '$full_desc', active = '0', modified_when = '".@mktime()."', modified_by = '".$admin->get_user_id()."' WHERE item_id = '$item_id'");

	// Check if there is a db error, otherwise say successful
	if ($database->is_error()) {
		$admin->print_error($database->get_error(), WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$id);
	} else {
		// Different targets depending on the save action
		if (isset($_POST['save_and_return']) AND $_POST['save_and_return'] != '') {
			$return_url = WB_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$item_id;
		}
		else {
			$return_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
		}
		// Print success message and return
		$admin->print_success($TEXT['SUCCESS'], $return_url);
	}
}




// Print admin footer
$admin->print_footer();

?>