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

$module_directory = 'bakery';
$module_name = 'Bakery fberke edition';
$module_function = 'page';
$module_version = '1.7.3';
$module_platform = '1.0';
$module_author = 'Christoph Marti, fberke';
$module_license = 'GNU General Public License';
$module_license_terms = '-';
$module_description = 'Bakery is a WebsiteBaker shop module with catalog, cart, stock administration, order administration and invoice/delivery note/reminder printing feature. Payment in advance, invoice and/or different payment gateways. Further information can be found on the <a href="http://www.bakery-shop.ch" target="_blank">Bakery Website</a>.';
$module_guid = 'd32d0843-300b-43f7-8e73-9e180b0aae54';
$module_home = 'http://bakery-shop.ch/';

?>
