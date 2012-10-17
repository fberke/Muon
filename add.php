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
 

// Look for language File
if (LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/bakery/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
    }
}


// Set default values for page settings

// Shop
$page_offline = "no";
$offline_text = $MOD_BAKERY['ERR_OFFLINE_TEXT'];
$continue_url = $page_id;

// Layout
$header = $admin->add_slashes('
<div id="item_overview_show_cart">
<form action="[SHOP_URL]" method="post">
<button type="submit" name="view_cart" value="[VIEW_CART]">[VIEW_CART]</button>
</form>
</div>	
');
$item_loop = $admin->add_slashes('
<div class="item_overview_wrapper">
<div class="item_overview_thumbs">
[THUMB]
</div>
<div class="item_overview_details">
<h3><a href="[LINK]">[TITLE]</a></h3>
<p class="item_short_desc">[DESCRIPTION]</p>
<p class="item_price">[TXT_PRICE]: [PRICE] [CURRENCY]
<br />([TAX_INFO])</p>
<p class="item_shipping_info">[PLUS_SHIPPING]</p>
<p class="item_stock_info">[TXT_STOCK]: [STOCK]</p>
<form action="[SHOP_URL]" method="post">
<input type="hidden" name="item[ITEM_ID]" value="1" />
<button type="submit" name="add_to_cart" value="[ADD_TO_CART]">[ADD_TO_CART]</button>
</form>
</div>
</div>
');
$footer = $admin->add_slashes('
<ul id="item_overview_footernav" [DISPLAY_PREVIOUS_NEXT_LINKS]>
<li>[PREVIOUS_PAGE_LINK]</li><!--
--><li>[TXT_ITEM] [OF]</li></--
--><li>[NEXT_PAGE_LINK]</li>
</ul>
');
$item_header = $admin->add_slashes('
<div id="item_details_wrapper">
<form action="[SHOP_URL]" method="post">

<div id="item_details_images">
[IMAGE]
[THUMBS]
</div><!--

--><div id="item_details_description">
<h1>[TITLE]</h1>
<p class="sku"><span>[TXT_SKU]:</span> [SKU]</p>
<p class="item_stock_info">[TXT_STOCK]: [STOCK]</p>
[FULL_DESC]

<fieldset>
<legend></legend>
[OPTION]
</fieldset>
<fieldset>
<legend></legend>
[TEXTAREA]
</fieldset>
</div><!--

--><div id="item_details_price_wrapper">
<div id="item_details_price_shipping">
<p class="item_price"><span>[TXT_PRICE]: </span>[PRICE] [CURRENCY]</p>
<p class="item_vat_info">[TAX_INFO]</p>
<p class="item_shipping"><span>[TXT_SHIPPING]:</span> [SHIPPING] [CURRENCY]</p>
<p class="shipping_cost"><span>[TXT_SHIPPING_COST]:</span><br />
[TXT_DOMESTIC]: [SHIPPING_DOMESTIC] [CURRENCY]<br />
[TXT_ABROAD]: [SHIPPING_ABROAD] [CURRENCY]</p>
</div>
<div id="item_details_submit">
<input type="text" name="item[ITEM_ID]" value="1" size="2" />
<button type="submit" name="add_to_cart" value="[ADD_TO_CART]">[ADD_TO_CART]</button>
</div>
</div>

</form>
</div>

<p class="item_previous_next">[PREVIOUS] | <a href="[BACK]">[TXT_BACK]</a> | [NEXT]</p>

[REALTIME_CALC]
');
$item_footer = $admin->add_slashes('');


// Insert default values into table page_settings 
$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_page_settings (section_id, page_id, page_offline, offline_text, continue_url, header, item_loop, footer, item_header, item_footer)
VALUES ('$section_id', '$page_id', '$page_offline', '$offline_text', '$continue_url', '$header', '$item_loop', '$footer', '$item_header', '$item_footer')");

?>
