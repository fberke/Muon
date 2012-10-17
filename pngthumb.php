<?php


/*
 * This file is almost completely copied verbatim from WebsiteBaker
 * sources, so make of that what you will with regard to copyright.
 *
 * -- Frank Schoep
 */

 
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
 

// Generate a thumbnail from an image
function make_thumb_png($source, $destination, $size) {
	// Check if GD is installed
	if (extension_loaded('gd') AND function_exists('imageCreateFromPng')) {
		// First figure out the size of the thumbnail
		list($original_x, $original_y) = getimagesize($source);
		if ($original_x > $original_y) {
			$thumb_w = $size;
			$thumb_h = $original_y*($size/$original_x);
		}
		if ($original_x < $original_y) {
			$thumb_w = $original_x*($size/$original_y);
			$thumb_h = $size;
		}
		if ($original_x == $original_y) {
			$thumb_w = $size;
			$thumb_h = $size;	
		}
		// Now make the thumbnail
		$source = imageCreateFromPng($source);
		$dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
		imagecopyresampled($dst_img,$source,0,0,0,0,$thumb_w,$thumb_h,$original_x,$original_y);
		imagejpeg($dst_img, $destination);
		// Clear memory
		imagedestroy($dst_img);
		imagedestroy($source);
		// Return true
		return true;
	} else {
   	return false;
	}
}

?>