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

// This file is covering Germany = DE


/* 
   The name of this file must correspond to the country code!
   Eg. for United States of America = US set the file name to US.php
   
   If this file is installed, it will be invoked automatically by Bakery.
*/


// Modify the state list to fit your needs by deleting lines or changing the lines order

$MOD_BAKERY['TXT_STATE_CODE'][1] = 'BW'; $MOD_BAKERY['TXT_STATE_NAME'][1] = 'Baden-W&uuml;rttemberg';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'BY'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Bayern';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'BE'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Berlin';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'BR'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Brandenburg';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'HB'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Bremen';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'HH'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Hamburg';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'HE'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Hessen';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'MV'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Mecklenburg-Vorpommern';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NI'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Niedersachsen';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NW'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Nordrhein-Westfalen';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'RP'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Rheinland-Pfalz';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'SL'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Saarland';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'SN'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Sachsen';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'ST'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Sachsen-Anhalt';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'SH'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Schleswig-Holstein';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'TH'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Th&uuml;ringen';

?>
