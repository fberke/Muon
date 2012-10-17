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

// This file is covering France = FR


/* 
   The name of this file must correspond to the country code!
   Eg. for United States of America = US set the file name to US.php
   
   If this file is installed, it will be invoked automatically by Bakery.
*/


// Modify the state list to fit your needs by deleting lines or changing the lines order

$MOD_BAKERY['TXT_STATE_CODE'][1] = '42'; $MOD_BAKERY['TXT_STATE_NAME'][1] = 'Alsace';
$MOD_BAKERY['TXT_STATE_CODE'][] = '72'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Aquitaine';
$MOD_BAKERY['TXT_STATE_CODE'][] = '83'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Auvergne';
$MOD_BAKERY['TXT_STATE_CODE'][] = '25'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Basse-Normandie';
$MOD_BAKERY['TXT_STATE_CODE'][] = '26'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Bourgogne';
$MOD_BAKERY['TXT_STATE_CODE'][] = '53'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Bretagne';
$MOD_BAKERY['TXT_STATE_CODE'][] = '24'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Centre';
$MOD_BAKERY['TXT_STATE_CODE'][] = '21'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Champagne-Ardenne';
$MOD_BAKERY['TXT_STATE_CODE'][] = '94'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Corse';
$MOD_BAKERY['TXT_STATE_CODE'][] = '43'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Franche-Comt&eacute;';
$MOD_BAKERY['TXT_STATE_CODE'][] = '01'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Guadeloupe';
$MOD_BAKERY['TXT_STATE_CODE'][] = '03'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Guyane';
$MOD_BAKERY['TXT_STATE_CODE'][] = '23'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Haute-Normandie';
$MOD_BAKERY['TXT_STATE_CODE'][] = '11'; $MOD_BAKERY['TXT_STATE_NAME'][] = '&Icirc;le-de-France';
$MOD_BAKERY['TXT_STATE_CODE'][] = '04'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'La R&eacute;union';
$MOD_BAKERY['TXT_STATE_CODE'][] = '91'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Languedoc-Roussillon';
$MOD_BAKERY['TXT_STATE_CODE'][] = '74'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Limousin';
$MOD_BAKERY['TXT_STATE_CODE'][] = '41'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Lorraine';
$MOD_BAKERY['TXT_STATE_CODE'][] = '02'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Martinique';
$MOD_BAKERY['TXT_STATE_CODE'][] = '73'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Midi-Pyr&eacute;n&eacute;es';
$MOD_BAKERY['TXT_STATE_CODE'][] = '31'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Nord-Pas-de-Calais';
$MOD_BAKERY['TXT_STATE_CODE'][] = '52'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Pays de la Loire';
$MOD_BAKERY['TXT_STATE_CODE'][] = '22'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Picardie';
$MOD_BAKERY['TXT_STATE_CODE'][] = '54'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Poitou-Charentes';
$MOD_BAKERY['TXT_STATE_CODE'][] = '93'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Provence-Alpes-C&ocirc;te d&apos;Azur';
$MOD_BAKERY['TXT_STATE_CODE'][] = '82'; $MOD_BAKERY['TXT_STATE_NAME'][] = 'Rh&ocirc;ne-Alpes';

?>
