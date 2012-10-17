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
 

// Resize PNG image
function resizePNG($source, $destination, $new_max_w, $new_max_h) {

	// Check if GD is installed
	if (extension_loaded('gd') AND function_exists('imagecreatefrompng')) {
		// First figure out the size of the image
		list($orig_w, $orig_h) = getimagesize($source);
		if ($orig_w > $new_max_w) {
			$new_w = $new_max_w;
			$new_h = intval($orig_h * ($new_w / $orig_w));
			if ($new_h > $new_max_h) {
				$new_h = $new_max_h;
				$new_w = intval($orig_w * ($new_h / $orig_h));
			}
		} else if ($orig_h > $new_max_h) {
			$new_h = $new_max_h;
			$new_w = intval($orig_w * ($new_h / $orig_h));
		} else {
			// Image to small to be downsized
			echo "<div align='center'><p style='color: red;'>Image to small to be downsized!</p></div>";
			return false;
		}
		// Now make the image
		$source = imagecreatefrompng($source);
		$dst_img = imagecreatetruecolor($new_w, $new_h);
		imagecopyresampled($dst_img, $source, 0,0,0,0, $new_w, $new_h, $orig_w, $orig_h);
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


// Resize JPEG image
function resizeJPEG($source, $new_max_w, $new_max_h, $quality = 75) {

	if ($img = imagecreatefromjpeg($source)) {
		$orig_w = imagesx($img);
		$orig_h = imagesy($img);
		$resize = FALSE;
		$handle;
		if ($orig_w > $new_max_w) {
			$new_w = $new_max_w;
			$new_h = intval($orig_h * ($new_w / $orig_w));
			if ($new_h > $new_max_h) {
				$new_h = $new_max_h;
				$new_w = intval($orig_w * ($new_h / $orig_h));
			}
			$resize = TRUE;
		} else if ($orig_h > $new_max_h) {
			$new_h = $new_max_h;
			$new_w = intval($orig_w * ($new_h / $orig_h));
			$resize = TRUE;
		} else {
			// Image cant be downsized
			echo "<div align='center'><p style='color: red;'>Image to small to be downsized!</p></div>";
			return false;
		}

		if ($resize) {
			// Resize using appropriate function
			if (function_exists('imagecopyresampled')) {
				$imageId = imagecreatetruecolor($new_w, $new_h);
				imagecopyresampled($imageId, $img, 0,0,0,0, $new_w, $new_h, $orig_w, $orig_h);
			} else {
				$imageId = imagecreate($new_w , $new_h);
				imagecopyresized($imageId, $img, 0,0,0,0, $new_w, $new_h, $orig_w, $orig_h);
			}
			$handle = $imageId;
			// Free original image
			imagedestroy($img);
		} else {
			$handle = $img;
		}
		imagejpeg($handle, $source, $quality);
		imagedestroy($handle);
	}
}


?>