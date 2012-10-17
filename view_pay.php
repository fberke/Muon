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
 

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Assign page filename and pagetitle for web analytics
global $bakery_analytics;
$bakery_analytics ['filename'] = str_replace(PAGE_EXTENSION,"",$setting_continue_url).$MOD_BAKERY_FILENAME ['payment_methods'];
$bakery_analytics ['pagetitle'] = $MOD_BAKERY_PAGETITLE ['payment_methods'];

// DIRECT CHECKOUT if required in general settings and if max 1 payment method
if ($skip_checkout) {
	$payment_method = $setting_payment_methods[0];
	if (is_file(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/processor.php')) {
		include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/processor.php');
	}
} else {

	// VIEW PAYMENT METHODS
	// Show header
	echo "<h2><img src='".WB_URL."/modules/bakery/images/$step_img_dir/step_3.gif' />\n {$MOD_BAKERY['TXT_CHECKOUT']}</h2>";
	
	// Only show paragraph if we have >1 payment methods
	if ($num_payment_methods > 1) {
		echo '<p class="mod_bakery_pay_method_f">'.$MOD_BAKERY['TXT_PAY_METHOD'].':</p>';
	}

	// Uncomment this, if you use the classic Bakery table layout
	/*
	echo "<table width='98%' border='0' cellspacing='0' cellpadding='0'>
	  <tr>
		<td colspan='2'><hr class='mod_bakery_hr_f' /></td>
	  </tr>";
	*/

	// Only show payment method/payment gateway if we have to
	if ($num_payment_methods > 0) {
		foreach ($setting_payment_methods as $payment_method) {
			if (is_file(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/processor.php')) {
				include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/processor.php');
			}
		}
	} else {
		echo '<div class="mod_bakery_error_f"><p>'.$MOD_BAKERY['TXT_NO_PAYMENT_METHOD'].'</p></div>';
	}
	// Uncomment this, if you use the classic Bakery table layout
	/*
	echo "</table>";
	*/
	
}



// AVOID OUTPUT FILTER
// Obtain the settings of the output filter module
if (file_exists(WB_PATH.'/modules/output_filter/filter-routines.php')) {
	include_once(WB_PATH.'/modules/output_filter/filter-routines.php');
	if (function_exists('getOutputFilterSettings')) {
		$filter_settings = getOutputFilterSettings();
	} else {
		$filter_settings = get_output_filter_settings();
	}
} else {
	// No output filter used, define default settings
	$filter_settings['email_filter'] = 0;
}

/*
	NOTE:
	With ob_end_flush() the output filter will be disabled for Bakery checkout page
	If you are using e.g. ob_start in the index.php of your template it is possible that you will indicate problems
*/
if ($filter_settings['email_filter'] && !($filter_settings['at_replacement']=='@' && $filter_settings['dot_replacement']=='.')) { 
	ob_end_flush();
}

?>