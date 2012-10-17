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
 


// SHOW ITEM DETAIL PAGE
// *********************

// Get page settings
$query_page_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_page_settings WHERE section_id = '$section_id'");
if ($query_page_settings->numRows() > 0) {
	$fetch_page_settings = $query_page_settings->fetchRow();
	$setting_item_header = stripslashes($fetch_page_settings['item_header']);
	$setting_item_footer = stripslashes($fetch_page_settings['item_footer']);
	$setting_lightbox2 = stripslashes($fetch_page_settings['lightbox2']);
} else {
	$setting_item_header = '';
	$setting_item_footer = '';
}
	
// If requested include lightbox2 (css is appended to the frontend.css stylesheet)
if ($setting_lightbox2 == "detail" || $setting_lightbox2 == "all") {
	?>
	<script type="text/javascript" src="<?php echo WB_URL; ?>/modules/bakery/lightbox2/js/prototype.js"></script>
	<script type="text/javascript" src="<?php echo WB_URL; ?>/modules/bakery/lightbox2/js/scriptaculous.js?load=effects,builder"></script>
	<script type="text/javascript">
	//  Lightbox2 configuration
	LightboxOptions = Object.extend({
		fileLoadingImage:        '<?php echo WB_URL; ?>/modules/bakery/lightbox2/images/loading.gif',     
		fileBottomNavCloseImage: '<?php echo WB_URL; ?>/modules/bakery/lightbox2/images/closelabel.gif',
		overlayOpacity: 0.7,   // controls transparency of shadow overlay
		animate: true,         // toggles resizing animations
		resizeSpeed: 7,        // controls the speed of the image resizing animations (1=slowest and 10=fastest)
		borderSize: 10,        // if you adjust the padding in the CSS, you will need to update this variable
		// When grouping images this is used to write: Image # of #.
		// Change it for non-english localization
		labelImage: "<?php echo $MOD_BAKERY['TXT_IMAGE']; ?>",
		labelOf: "<?php echo $TEXT['OF']; ?>"
	}, window.LightboxOptions || {});
	</script>
	<script type="text/javascript" src="<?php echo WB_URL; ?>/modules/bakery/lightbox2/js/lightbox.js"></script>
	<?php
}

// Get page info
$query_page = $database->query("SELECT link FROM ".TABLE_PREFIX."pages WHERE page_id = '".PAGE_ID."'");
if ($query_page->numRows() > 0) {
	$page = $query_page->fetchRow();
	$page_link = page_link($page['link']);
	if (isset($_GET['p']) AND $position > 0) {
		$page_link .= '?p='.$_GET['p'];
	}
} else {
	exit('Page not found');
}

// Get total number of items
$query_total_num = $database->query("SELECT item_id FROM ".TABLE_PREFIX."mod_bakery_items WHERE section_id = '$section_id' AND active = '1' AND title != ''");
$total_num = $query_total_num->numRows();

// Get item info
$query_item = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_bakery_items WHERE item_id = '".ITEM_ID."' AND active = '1'");
if ($query_item->numRows() > 0) {
	// Initialize vars
	$next_link     = $MOD_BAKERY['NEXT_ITEM'];
	$previous_link = $MOD_BAKERY['PREVIOUS_ITEM'];
	$item = $query_item->fetchRow();	
	$position = $item['position'];
	$title = htmlspecialchars(stripslashes($item['title']));
	$price = number_format(stripslashes($item['price']), 2, $setting_dec_point, $setting_thousands_sep);
	$price_raw = stripslashes($item['price']);

	// Create previous and next links
	$query_surrounding = $database->query("SELECT item_id FROM ".TABLE_PREFIX."mod_bakery_items WHERE position != '$position' AND section_id = '$section_id' AND active = '1' LIMIT 1");
	if ($query_surrounding->numRows() > 0) {
		
		// Get previous
		if ($position > 1) {
			$query_previous = $database->query("SELECT link FROM ".TABLE_PREFIX."mod_bakery_items WHERE position < '$position' AND section_id = '$section_id' AND active = '1' ORDER BY position DESC LIMIT 1");
			if ($query_previous->numRows() > 0) {
				$previous = $query_previous->fetchRow();
				$previous_link = '<a href="'.WB_URL.PAGES_DIRECTORY.$previous['link'].PAGE_EXTENSION.'">'.$MOD_BAKERY['PREVIOUS_ITEM'].'</a>';
			}
		}
		// Get next
		$query_next = $database->query("SELECT link FROM ".TABLE_PREFIX."mod_bakery_items WHERE position > '$position' AND section_id = '$section_id' AND active = '1' ORDER BY position ASC LIMIT 1 ");
		if ($query_next->numRows() > 0) {
			$next = $query_next->fetchRow();
			$next_link = '<a href="'.WB_URL.PAGES_DIRECTORY.$next['link'].PAGE_EXTENSION.'"> '.$MOD_BAKERY['NEXT_ITEM'].'</a>';
		}
	}

	$out_of = $position.' '.strtolower($TEXT['OUT_OF']).' '.$total_num;
	$of     = $position.' '.strtolower($TEXT['OF']).' '.$total_num;
	
	// User who last modified the item
	$uid = $item['modified_by'];
	
	// Workout date and time of last modified item
	$item_date = date(DEFAULT_DATE_FORMAT, $item['modified_when']);
	$item_time = date(DEFAULT_TIME_FORMAT, $item['modified_when']);



	// Item thumb(s) and image(s)
	
	// Initialize or reset thumb(s) and image(s) befor laoding next item
	$thumb_arr = array();
	$image_arr = array();
	$thumb = "";
	$image = "";

	// Prepare thumb and image directory pathes and urls
	$thumb_dir = WB_PATH.MEDIA_DIRECTORY.'/bakery/thumbs/item'.ITEM_ID.'/';
	$img_dir = WB_PATH.MEDIA_DIRECTORY.'/bakery/images/item'.ITEM_ID.'/';
	$thumb_url = WB_URL.MEDIA_DIRECTORY.'/bakery/thumbs/item'.ITEM_ID.'/';
	$img_url = WB_URL.MEDIA_DIRECTORY.'/bakery/images/item'.ITEM_ID.'/';
	
	// Check if the thumb and image directories exist
	if (is_dir($thumb_dir) && is_dir($img_dir)) {
		// Open the image directory then loop through its contents
		$dir = dir($img_dir);
		while (false !== $image_file = $dir->read()) {
			// Skip index file and pointers
			if (strpos($image_file, '.php') !== false || substr($image_file, 0, 1) == ".") {
				continue;
			}
			// Thumbs use .jpg extension only
			$thumb_file = str_replace (".png", ".jpg", $image_file);
				
			// Convert filename to lightbox2 title
			$img_title = str_replace(array(".png", ".jpg"), "", $image_file);
			$img_title = str_replace("_", " ", $img_title);

			// Make array of all item thumbs and images
			if (file_exists($thumb_dir.$thumb_file) && file_exists($img_dir.$image_file)) {
				// If needed add lightbox2 link to the thumb/image...
				if ($setting_lightbox2 == "detail" || $setting_lightbox2 == "all") {
					$prepend = "<a href='".$img_url.$image_file."' rel='lightbox[image_".ITEM_ID."]' title='".$img_title."'><img src='";
					$thumb_append = "' alt='".$img_title."' title='".$img_title."' class='mod_bakery_item_thumb_f' /></a>";
					$img_append = "' alt='".$img_title."' title='".$img_title."' class='mod_bakery_item_img_f' /></a>";
				// ...else add thumb/image only
				} else {
					$prepend = "<img src='";
					$thumb_append = "' alt='".$img_title."' title='".$img_title."' class='mod_bakery_item_thumb_f' />";
					$img_append = "' alt='".$img_title."' title='".$img_title."' class='mod_bakery_item_img_f' />";
				}
				// Check if a main thumb/image is set
				if ($image_file == $item['main_image']) {
					$thumb = $prepend.$thumb_url.$thumb_file.$img_append;
					$image = $prepend.$img_url.$image_file.$img_append;
					continue;
				}
				// Make array
				$thumb_arr[] = $prepend.$thumb_url.$thumb_file.$thumb_append;
				$image_arr[] = $prepend.$img_url.$image_file.$img_append;
			}
		}
	}
	
	// Make strings for use in the item templates
	$thumbs = implode("\n", $thumb_arr);
	$images = implode("\n", $image_arr);



	// Show item options and attributes if we have to
	
	// Initialize vars
	$option = "";
	
	// table output
	// $option_select = "<tr>\n";
	
	// accessible output
	$option_select = "";
	
	// create JS array of attribute prices for real-time price calculation
	$select_ids = "";
	$attribute_prices = "";
	
	// Get number of item options and loop for each of them
	$query_num_options = $database->query("SELECT DISTINCT o.option_name, ia.option_id FROM ".TABLE_PREFIX."mod_bakery_options o INNER JOIN ".TABLE_PREFIX."mod_bakery_item_attributes ia ON o.option_id = ia.option_id WHERE ia.item_id = ".ITEM_ID." ORDER BY o.option_name");			
	if ($query_num_options->numRows() > 0) {
	$j = 0;
		while ($num_options = $query_num_options->fetchRow()) {
			$option_name = stripslashes($num_options['option_name']);
			$option_id = stripslashes($num_options['option_id']);

			// Get item attributes
			$query_attributes = $database->query("SELECT 
				o.option_name,
				a.attribute_name,
				ia.attribute_id,
				ia.price,
				ia.operator
				FROM ".TABLE_PREFIX."mod_bakery_options 
				o INNER JOIN ".TABLE_PREFIX."mod_bakery_attributes
				a ON
				o.option_id = a.option_id
				INNER JOIN ".TABLE_PREFIX."mod_bakery_item_attributes
				ia ON
				a.attribute_id = ia.attribute_id
				WHERE item_id = ".ITEM_ID." AND ia.option_id = '$option_id'
				ORDER BY
				o.option_name,
				ia.price
				ASC");
			if ($query_attributes->numRows() > 0) {
				// table output
				// $option_select .= "<td><span class='mod_bakery_item_option_f'>".$option_name.": </span></td>\n<td valign='top'>\n<select name='attribute[]' class='mod_bakery_item_select_f'>";
				
				// accessible output
				$select_id = 'select_'.$option_id;
				$select_ids .= 'selectIDs["'.$j.'"]="'.$select_id.'";'."\n";
				$option_select .= '<li>'."\n";
				$option_select .= '<label for='.$select_id.'>'.$option_name.'</label>'."\n";
				$option_select .= '<select id="'.$select_id.'" name="attribute[]" onchange="calculateTotal()">'."\n";
//				$option_select .= '<select id="'.$select_id.'" name="attribute[]" onchange="loopSelected()">'."\n";
				
				$i = 0;
				while ($attributes = $query_attributes->fetchRow()) {
					$attributes = array_map('stripslashes', $attributes);
					// fill JS array
					$attribute_prices .= 'attrPrices["'.$attributes['attribute_id'].'"]='.$attributes['operator'].$attributes['price'].';'."\n";
					// Make attribute select
					$attributes['operator'] = $attributes['operator'] == "=" ? "" : $attributes['operator'];
					$ia_price = ", ".$attributes['operator'].$attributes['price']." ".$setting_shop_currency;
					$ia_price = $attributes['price'] == 0 ? "" : $ia_price;
					if ($i == 0) {
						$option_select .= '<option value="'.$attributes['attribute_id'].'" selected="selected">'.$attributes['attribute_name'].$ia_price.'</option>'."\n";
					} else {
						$option_select .= '<option value="'.$attributes['attribute_id'].'">'.$attributes['attribute_name'].$ia_price.'</option>'."\n";
					}
					$i++;
				}
				// table output
				// $option_select .= "</select>\n</td></tr>";
				
				// accessible output
				$option_select .= '</select>'."\n";
				$option_select .= '</li>'."\n";
			}
			$j++;
		}
		// standard (table) output
		// $option = $option_select;
		
		// accessible output
		$option = "<ol>\n".$option_select."</ol>\n";
	}
	
	// Create textarea row
	// Standard Bakery Table Output
	/*
	$textarea = "<tr>\n";
	$textarea .= "<td>".$MOD_BAKERY['TXT_VIEW_ITEM_TEXTAREA']."</td>\n";
	$textarea .= "<td><textarea name=\"view_item_textarea\" id=\"view_item_textarea\" cols=\"35\" rows=\"5\"></textarea></td>\n";
	$textarea .= "</tr>\n";
	*/
	
	// Alternative output (comment this out if you need the standard table-based output above)
	$textarea = "<label for=\"view_item_textarea\">".$MOD_BAKERY['TXT_VIEW_ITEM_TEXTAREA']."\n";
	$textarea .= "<textarea name=\"view_item_textarea\" id=\"view_item_textarea\" cols=\"35\" rows=\"5\"></textarea>\n";
	$textarea .= "</label>\n";
	
	// Output JS for real-time price calculation
	$realtime_calc = '<script type="text/javascript">'."\n";

	$realtime_calc .= 'var decimalPoint = "'.$setting_dec_point.'";'."\n";
	$realtime_calc .= 'var thousandsSep = "'.$setting_thousands_sep.'";'."\n";
	$realtime_calc .= 'var basePrice = '.$item['price'].';'."\n";
	
	$realtime_calc .= 'var selectIDs = new Array();'."\n";
	$realtime_calc .= $select_ids;
	
	$realtime_calc .= 'var attrPrices = new Array();'."\n";
	$realtime_calc .= $attribute_prices;

	$realtime_calc .= '</script>'."\n";
	$realtime_calc .= '<script type="text/javascript" src="'.WB_URL.'/modules/bakery/number_format.js"></script>'."\n";
	$realtime_calc .= '<script type="text/javascript" src="'.WB_URL.'/modules/bakery/rpc.js"></script>'."\n";
	
	
	// Check if we should show number of items, stock image or "in stock" message or nothing at all
	$item_stock = stripslashes($item['stock']);
	// Only show if item stock is not blank
	if ($item_stock == '' && $setting_stock_mode != "none") {
		$stock = $MOD_BAKERY['TXT_NA'];
	} else {
		// Display number of items
		if ($setting_stock_mode == "number") {
			if ($item_stock < 1) {
				$stock = 0;
			} else {
				$stock = $item_stock;
			}
		// Display stock image
		} elseif ($setting_stock_mode == "img" && is_numeric($setting_stock_limit) && $setting_stock_limit != "") {
			if ($item_stock < 1) {
				$stock = "<img src='".WB_URL."/modules/bakery/images/out_of_stock.gif' alt='".$MOD_BAKERY['TXT_OUT_OF_STOCK']."' class='mod_bakery_item_stock_img_f' />";
			} elseif ($item_stock > $setting_stock_limit) {
				$stock = "<img src='".WB_URL."/modules/bakery/images/in_stock.gif' alt='".$MOD_BAKERY['TXT_IN_STOCK']."' class='mod_bakery_item_stock_img_f' />";
			} else {
				$stock = "<img src='".WB_URL."/modules/bakery/images/short_of_stock.gif' alt='".$MOD_BAKERY['TXT_SHORT_OF_STOCK']."' class='mod_bakery_item_stock_img_f' />";
		}
		// Display stock text message			
		} elseif ($setting_stock_mode == "text" && is_numeric($setting_stock_limit) && $setting_stock_limit != "") {
			if ($item_stock < 1) {
				$stock = "<span class='mod_bakery_item_out_of_stock_f'>".$MOD_BAKERY['TXT_OUT_OF_STOCK']."</span>";
			} elseif ($item_stock > $setting_stock_limit) {
				$stock = "<span class='mod_bakery_item_in_stock_f'>".$MOD_BAKERY['TXT_IN_STOCK']."</span>";
			} else {
				$stock = "<span class='mod_bakery_item_short_of_stock_f'>".$MOD_BAKERY['TXT_SHORT_OF_STOCK']."</span>";
			}
		// Display nothing
		} else {
			$stock = "";
		}
	}

	// Replace [wblinkPAGE_ID] generated by wysiwyg editor by real link
	$item['full_desc'] = stripslashes($item['full_desc']);
	$pattern = '/\[wblink(.+?)\]/s';
	preg_match_all($pattern,$item['full_desc'],$ids);
	foreach ($ids[1] as $page_id) {
		$pattern = '/\[wblink'.$page_id.'\]/s';
		// Get page link
		$query_pages = $database->query("SELECT link FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id' LIMIT 1");
		$page = $query_pages->fetchRow();
		$link = WB_URL.PAGES_DIRECTORY.$page['link'].PAGE_EXTENSION;
		$item['full_desc'] = preg_replace($pattern,$link,$item['full_desc']);
	}
	if ($item['full_desc'] != "") {
		$item['full_desc'] = "<div id=\"item_details_fulldesc\">\n".$item['full_desc']."</div>\n";
	}

	// Replace placeholders by values
	$vars = array(
		'[ADD_TO_CART]',
		'[PAGE_TITLE]',
		'[THUMB]',
		'[THUMBS]',
		'[IMAGE]',
		'[IMAGES]', 
		'[TITLE]', 
		'[ITEM_ID]',
		'[CATEGORY]',
		'[SKU]', 
		'[STOCK]',
		'[PRICE]',
		'[PRICE_RAW]',
		'[TAX_RATE]', 
		'[TAX_INFO]',
		'[SHIPPING]',
		'[FIELD_1]', 
		'[FIELD_2]',
		'[FIELD_3]', 
		'[OPTION]', 
		'[TEXTAREA]',
		'[REALTIME_CALC]',
		'[DESCRIPTION]',
		'[CHARACTERISTICS]',
		'[FULL_DESC]', 
		'[SHOP_URL]',
		'[SHIPPING_DOMESTIC]',
		'[SHIPPING_ABROAD]',
		'[SHIPPING_D_A]', 
		'[CURRENCY]', 
		'[BACK]',
		'[DATE]', 
		'[TIME]', 
		'[USER_ID]', 
		'[USERNAME]', 
		'[DISPLAY_NAME]',
		'[EMAIL]',
		'[PREVIOUS]', 
		'[NEXT]', 
		'[OUT_OF]', 
		'[OF]', 
		'[TEXT_OUT_OF]', 
		'[TEXT_OF]', 
		'[TXT_ITEM]',
		'[TXT_SKU]', 
		'[TXT_STOCK]',
		'[TXT_PRICE]', 
		'[TXT_TAX_RATE]',
		'[TXT_SHIPPING]', 
		'[TXT_FIELD_1]', 
		'[TXT_FIELD_2]', 
		'[TXT_FIELD_3]', 
		'[TXT_FULL_DESC]',
		'[TXT_SHIPPING_COST]',
		'[TXT_DOMESTIC]', 
		'[TXT_ABROAD]', 
		'[TXT_BACK]'
		);
	
	if (isset($users[$uid]['username']) AND $users[$uid]['username'] != '') {
		$values = array(
			$MOD_BAKERY['TXT_ADD_TO_CART'],
			PAGE_TITLE,
			$thumb,
			$thumbs,
			$image,
			$images,
			$title, 
			ITEM_ID,
			PAGE_TITLE,
			stripslashes($item['sku']),
			$stock,
			$price,
			$price_raw,
			stripslashes($item['tax_rate']),
			$tax_info,
			stripslashes($item['shipping']),
			stripslashes($item['definable_field_0']),
			stripslashes($item['definable_field_1']),
			stripslashes($item['definable_field_2']),
			$option,
			$textarea,
			$realtime_calc,
			stripslashes($item['description']),
			stripslashes($item['characteristics']),
			$item['full_desc'], 
			$setting_continue_url, 
			$setting_shipping_domestic,
			$setting_shipping_abroad,
			$setting_shipping_d_a,
			$setting_shop_currency,
			$page_link,
			$item_date,
			$item_time, 
			$uid, 
			$users[$uid]['username'],
			$users[$uid]['display_name'],
			$users[$uid]['email'], 
			$previous_link, 
			$next_link, 
			$out_of,
			$of,  
			$TEXT['OUT_OF'],
			$TEXT['OF'], 
			$MOD_BAKERY['TXT_ITEM'],
			$MOD_BAKERY['TXT_SKU'],
			$MOD_BAKERY['TXT_STOCK'],
			$MOD_BAKERY['TXT_PRICE'], 
			$MOD_BAKERY['TXT_TAX_RATE'], 
			$MOD_BAKERY['TXT_SHIPPING'],
			$setting_definable_field_0,
			$setting_definable_field_1,
			$setting_definable_field_2,
			$MOD_BAKERY['TXT_FULL_DESC'],
			$MOD_BAKERY['TXT_SHIPPING_COST'],
			$MOD_BAKERY['TXT_DOMESTIC'], 
			$MOD_BAKERY['TXT_ABROAD'], 
			$MOD_BAKERY['ITEM_OVERVIEW']
			);
	} else {
		$values = array(
			$MOD_BAKERY['TXT_ADD_TO_CART'], 
			PAGE_TITLE,
			$thumb,
			$thumbs,
			$image,
			$images, 
			$title, 
			ITEM_ID,
			PAGE_TITLE,
			stripslashes($item['sku']),
			$stock,
			$price,
			$price_raw,
			stripslashes($item['tax_rate']),
			$tax_info,
			stripslashes($item['shipping']),
			stripslashes($item['definable_field_0']),
			stripslashes($item['definable_field_1']),
			stripslashes($item['definable_field_2']),
			$option,
			$textarea,
			$realtime_calc,
			stripslashes($item['description']),
			stripslashes($item['characteristics']),
			$item['full_desc'], 
			$setting_continue_url,
			$setting_shipping_domestic,
			$setting_shipping_abroad,
			$setting_shipping_d_a,
			$setting_shop_currency, 
			$page_link, 
			$item_date, 
			$item_time, 
			'', 
			'', 
			'', 
			'', 
			$previous_link,
			$next_link, 
			$out_of,
			$of, 
			$TEXT['OUT_OF'],
			$TEXT['OF'], 
			$MOD_BAKERY['TXT_ITEM'], 
			$MOD_BAKERY['TXT_SKU'],
			$MOD_BAKERY['TXT_STOCK'],
			$MOD_BAKERY['TXT_PRICE'],
			$MOD_BAKERY['TXT_TAX_RATE'],
			$MOD_BAKERY['TXT_SHIPPING'],
			$setting_definable_field_0,
			$setting_definable_field_1,
			$setting_definable_field_2,
			$MOD_BAKERY['TXT_FULL_DESC'], 
			$MOD_BAKERY['TXT_SHIPPING_COST'],
			$MOD_BAKERY['TXT_DOMESTIC'],
			$MOD_BAKERY['TXT_ABROAD'], 
			$MOD_BAKERY['ITEM_OVERVIEW']
			);
	}

	// Print item header
	echo str_replace($vars, $values, $setting_item_header);

	// Print item footer
	echo str_replace($vars, $values, $setting_item_footer);

} else {
	echo $TEXT['NONE_FOUND'];
	return;
}

?>