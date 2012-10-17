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
 
// Direct checkout
if ($skip_checkout) {
	$payment_status = "success";
	include(WB_PATH.'/modules/bakery/view_confirmation.php');
	return;
}

// Include info file
include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/info.php');

// Look for payment method language file
if (LANGUAGE_LOADED) {
    include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php')) {
        include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php');
    }
}
?>


<h2><?PHP echo $MOD_BAKERY[$payment_method]['TXT_COD_PAYMENT']; ?></h2>

<ol class="payment_steps">
<li>
	<p><?PHP echo $MOD_BAKERY[$payment_method]['TXT_SUCCESS']; ?></p>
</li>
<li>
	<p><?PHP echo $MOD_BAKERY[$payment_method]['TXT_ACCOUNT']; ?></p>
</li>
<li>
	<p><?PHP echo $MOD_BAKERY[$payment_method]['TXT_SHIPMENT']; ?></p>
</li>
</ol>

<form action="<?php echo $setting_continue_url ?>" method="post">
	<input type="hidden" name="payment_method" value="<?php echo $payment_method ?>">
	<button type="submit" value="<?php echo $MOD_BAKERY[$payment_method]['TXT_PAY']; ?>"><?php echo $MOD_BAKERY[$payment_method]['TXT_PAY']; ?></button>
</form>