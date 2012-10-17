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
 

$PRECHECK = array();


/**
 * Specify required WebsiteBaker version
 * You need to provide at least the Version number (Default operator:= >=)
 */
$PRECHECK['WB_VERSION'] = array('VERSION' => '2.7', 'OPERATOR' => '>=');
#$PRECHECK['WB_VERSION'] = array('VERSION' => '2.7');


/**
 * Specify required PHP version
 * You need to provide at least the Version number (Default operator:= >=)
 */
#$PRECHECK['PHP_VERSION'] = array('VERSION' => '5.2.4');
$PRECHECK['PHP_VERSION'] = array('VERSION' => '4.3.11', 'OPERATOR' => '>=');


/**
 * Specify required PHP extension
 * Provide a simple array with the extension required by the module
 */
$PRECHECK['PHP_EXTENSIONS'] = array('gd');


/**
 * Specify required PHP INI settings
 * Provide an array with the setting and the expected value
 */
$PRECHECK['PHP_SETTINGS'] = array('safe_mode' => '0');


?>