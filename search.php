<?php

/*
 * This code is based on wb_searchext_mod_bakery v2.2 by thorn.
 * It is adopted to Bakery v0.9 by thorn (thanks to thorn!).
 * For further information see:
 * http://nettest.thekk.de/pages/testing/new-search-function.php
*/

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
 


function bakery_search($func_vars) {
	extract($func_vars, EXTR_PREFIX_ALL, 'func');
	
	// how many lines of excerpt we want to have at most
	$max_excerpt_num = $func_default_max_excerpt;
	// show thumbnails?
	$show_thumb = true;
	// show option-attributes?
	$show_options = true;
	$divider = ".";
	$result = false;

	$table_item     = TABLE_PREFIX."mod_bakery_items";
	$table_item_att = TABLE_PREFIX."mod_bakery_item_attributes";
	$table_att      = TABLE_PREFIX."mod_bakery_attributes";

	// fetch all active bakery-items in this section
	// don't care whether the shop is offline
	$query = $func_database->query("
		SELECT `item_id`, `title`, `sku`, `link`, `main_image`, `description`, `full_desc`, `modified_when`, `modified_by`
		FROM `$table_item`
		WHERE `section_id`='$func_section_id' AND `active` = '1'
		ORDER BY `title` ASC
	");
	
	// now call print_excerpt() for every single item
	if ($query->numRows() > 0) {
		while ($res = $query->fetchRow()) {
			// $res['link'] contains PAGES_DIRECTORY/bakery/... (e.g. "/pages/bakery/...")
			// remove the leading PAGES_DIRECTORY
			$page_link = preg_replace('/^\\'.PAGES_DIRECTORY.'/', '', $res['link'], 1);
			// thumbnail
			$pic_link = '';
			if ($show_thumb) {
				$thumb_dir = '/bakery/thumbs/item'.$res['item_id'].'/';
				if (is_file(WB_PATH.MEDIA_DIRECTORY.$thumb_dir.$res['main_image'])) {
					$pic_link = $thumb_dir.$res['main_image'];
				}
			}
			// option_attributes
			$options = '.';
			if ($show_options) {
				$query_att = $func_database->query("
					SELECT `attribute_name`
					FROM `$table_item_att` INNER JOIN `$table_att` USING(`attribute_id`)
					WHERE `item_id` = '{$res['item_id']}'
					ORDER BY `$table_att`.`option_id` ASC
				");
				echo mysql_error();
				if ($query_att->numRows() > 0) {
					while ($res_att = $query_att->fetchRow()) {
						$options .= $res_att['attribute_name'].'.';
					}
				}
			}

			$mod_vars = array(
				'page_link' => $page_link,
				'page_link_target' => "#wb_section_$func_section_id",
				
				// "item-title" as link, and "description" as description
				'page_title' => $res['title'],
				'page_description' => $res['description'],
				// or page_title as link, and  "item-title" as description
			//	'page_title' => $func_page_title,
			//	'page_description' => $res['title'],
				
				'page_modified_when' => $res['modified_when'],
				'page_modified_by' => $res['modified_by'],
				'text' => $res['title'].$divider.$res['description'].$divider.$res['full_desc'].$divider.$options.$divider.$res['sku'].$divider,
				'max_excerpt_num' => $max_excerpt_num,
				'pic_link' => $pic_link
			);
			if (print_excerpt2($mod_vars, $func_vars)) {
				$result = true;
			}
		}
	}
	return $result;
}

?>
