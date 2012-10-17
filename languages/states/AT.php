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
 

// STATES
// ******

// This file is covering Austria = AT


/* 
   The name of this file must correspond to the country code!
   Eg. for United States of America = US set the file name to US.php
   
   If this file is installed, it will be invoked automatically by Bakery.
*/


// Modify the state list to fit your needs by deleting lines or changing the lines order

$MOD_BAKERY['TXT_STATE_CODE'][1] = 'B'; $MOD_BAKERY['TXT_STATE_NAME'][1] = 'Burgenland';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'K'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'K&auml;rnten';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NO'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Nieder&ouml;sterreich';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'OO'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Ober&ouml;sterreich';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'S'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Salzburg';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'St'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Steiermark';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'T'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Tirol';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'V'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Vorarlberg';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'W'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Wien';

?>
