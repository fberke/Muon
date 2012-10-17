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
 

// Make use of the skinable backend themes of WB > 2.7
// Check if THEME_URL is supported otherwise use ADMIN_URL
if (!defined('THEME_URL')) {
	define('THEME_URL', ADMIN_URL);
}

//Look for language File
if (LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/bakery/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
    }
}

// Include WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
if (file_exists(WB_PATH.'/framework/module.functions.php') && file_exists(WB_PATH.'/modules/edit_module_files.php')) {
	include_once(WB_PATH.'/framework/module.functions.php');
}

// Delete empty Database records
$database->query("DELETE FROM ".TABLE_PREFIX."mod_bakery_items WHERE page_id = '$page_id' and section_id = '$section_id' and title=''");

// Get shop name
$query_general_settings = $database->query("SELECT shop_name, display_settings FROM ".TABLE_PREFIX."mod_bakery_general_settings");
if ($query_general_settings->numRows() > 0) {
	$fetch_general_settings = $query_general_settings->fetchRow();
	$shop_name = stripslashes($fetch_general_settings['shop_name']);
	$display_settings = "inline";
	if ($fetch_general_settings['display_settings'] == "1") {
		$display_settings = "none";
		if ($_SESSION['USER_ID'] == 1) {
			$display_settings = "inline";
		}
	}
} 

?>
<div id="mod_bakery_modify_b">

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td colspan="2" rowspan="2" align="left"><h2 class="mod_bakery_section_header_b"><?php echo strtoupper($shop_name); ?> &nbsp;&nbsp;<span><?php echo $TEXT['PAGE_TITLE'].": ".get_page_title($page_id)." | ".$TEXT['SECTION'].": ".$section_id; ?></span></h2></td>
	<td align="right" valign="bottom">
		<input type="button" value="<?php echo $MOD_BAKERY['TXT_GENERAL_SETTINGS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/bakery/modify_general_settings.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 200px; display: <?php echo $display_settings; ?>;" /></td>
</tr>
<tr>
	<td align="right" valign="top">
		<input type="button" value="<?php echo $MOD_BAKERY['TXT_PAGE_SETTINGS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/bakery/modify_page_settings.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 200px; display: <?php echo $display_settings; ?>;" /></td>
</tr>
<tr>
	<td align="left" width="33%">
		<input type="button" style="width: 80%;" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/bakery/modify_options.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>';" value="<?php echo $MOD_BAKERY['TXT_ITEM_OPTIONS']; ?>" />	</td>
	<td align="center" width="33%">
		<input type="button" value="<?php echo $MOD_BAKERY['TXT_ORDER_ADMIN']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/bakery/modify_orders.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 80%;" />	</td>
	<td align="right">
		<input type="button" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/bakery/modify_payment_methods.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>';" value="<?php echo $MOD_BAKERY['TXT_PAYMENT_METHODS']; ?>" style="width: 200px; display: <?php echo $display_settings; ?>;" /></td>
</tr>
<tr>
	<td align="left" width="33%">
		<input type="button" value="<?php echo $MOD_BAKERY['TXT_ADD_ITEM']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/bakery/add_item.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 80%;" />	</td>
	<td align="center" width="33%">
		<input type="button" value="<?php echo $MOD_BAKERY['TXT_STOCK_ADMIN']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/bakery/stock.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 80%;" />	</td>
	<td align="right">
		<?php
		if (function_exists('edit_module_css')) {
			if ($display_settings == "inline") {
				edit_module_css('bakery');
			}
		} else {
			echo "<input type='button' name='edit_module_file' class='mod_bakery_edit_css' value='{$TEXT['CAP_EDIT_CSS']}' onclick=\"javascript: alert('To take advantage of this feature please upgrade to WB 2.7 or higher.')\" />";
		}
		?>	</td>
</tr>
</table>

<br />
<h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$MOD_BAKERY['TXT_ITEM']; ?></h2>

<?php

// Loop through existing items
$query_items = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_items` WHERE section_id = '$section_id' ORDER BY position ASC");
if ($query_items->numRows() > 0) {
	$num_items = $query_items->numRows();
	$row = 'a';
	?>
	<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<?php
	while ($post = $query_items->fetchRow()) {
		?>
		<tr class="row_<?php echo $row; ?>" height="20">
			<td width="20" style="padding-left: 5px;">
				<a href="<?php echo WB_URL; ?>/modules/bakery/modify_item.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&item_id=<?php echo $post['item_id']; ?>" title="<?php echo $TEXT['MODIFY']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/modify_16.png" border="0" alt="<?php echo $TEXT['MODIFY']; ?>" />
				</a>
			</td>
			<td>
				<a href="<?php echo WB_URL; ?>/modules/bakery/modify_item.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&item_id=<?php echo $post['item_id']; ?>">
					<?php echo stripslashes($post['title']); ?>
				</a>
			</td>
			<td width="80">
				<?php echo $TEXT['ACTIVE'].': '; if ($post['active'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; } ?>
			</td>
			<td width="20">
			<?php if ($post['position'] != 1) { ?>
				<a href="<?php echo WB_URL; ?>/modules/bakery/move_up.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&item_id=<?php echo $post['item_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/up_16.png" border="0" alt="^" />
				</a>
			<?php } ?>
			</td>
			<td width="20">
			<?php if ($post['position'] != $num_items) { ?>
				<a href="<?php echo WB_URL; ?>/modules/bakery/move_down.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&item_id=<?php echo $post['item_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/down_16.png" border="0" alt="v" />
				</a>
			<?php } ?>
			</td>
			<td width="20">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/bakery/delete_item.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&item_id=<?php echo $post['item_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		<?php
		// Alternate row color
		if ($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	?>
	</table>
	<?php
} else {
	echo $TEXT['NONE_FOUND'];
}
?>
</div> <!-- enddiv #mod_bakery_modify_b -->