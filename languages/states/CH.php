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

// This file is covering Switzerland = CH


/* 
   The name of this file must correspond to the country code!
   Eg. for United States of America = US set the file name to US.php
   
   If this file is installed, it will be invoked automatically by Bakery.
*/


// Modify the state list to fit your needs by deleting lines or changing the lines order

$MOD_BAKERY['TXT_STATE_CODE'][1] = 'AG'; $MOD_BAKERY['TXT_STATE_NAME'][1] = 'Aargau';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'AR'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Appenzell Ausserrhoden';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'AI'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Appenzell Innerrhoden';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'BL'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Basel-Land';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'BS'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Basel-Stadt';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'BE'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Bern';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'FR'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Freiburg';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'GE'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Genf';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'GL'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Glarus';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'GR'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Graub&uuml;nden';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'JU'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Jura';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'LU'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Luzern';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NE'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Neuenburg';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'NW'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Nidwalden';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'OW'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Obwalden';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'SH'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Schaffhausen';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'SZ'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Schwyz';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'SO'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Solothurn';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'SG'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'St. Gallen';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'TI'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Tessin';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'TG'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Thurgau';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'UR'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Uri';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'VD'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Waadt';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'VS'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Wallis';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'ZG'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Zug';
$MOD_BAKERY['TXT_STATE_CODE'][] = 'ZH'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Z&uuml;rich';

?>
