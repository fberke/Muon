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

// This file is covering Canada = CA


/* 
   The name of this file must correspond to the country code!
   Eg. for United States of America = US set the file name to US.php
   
   If this file is installed, it will be invoked automatically by Bakery.
*/


// Modify the state list to fit your needs by deleting lines or changing the lines order

$MOD_BAKERY['TXT_STATE_CODE'][1] = 'AB'; $MOD_BAKERY['TXT_STATE_NAME'][1] = 'Alberta';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'BC'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'British Columnbia';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'MB'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Manitoba';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NB'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'New Brunswick';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NL'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Newfoundland and Labrador';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NT'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Northwest Territories';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NS'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Nova Scotia';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NU'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Nunavut';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'ON'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Ontario';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'PE'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Prince Edward Island';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'QC'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Quebec';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'SK'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Saskatchewan';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'YT'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Yukon';


?>