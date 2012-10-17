<?php

require('../../config.php');


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


require_once(WB_PATH.'/framework/class.admin.php');

// Check if GET and SESSION vars are set
if (!isset($_GET['page_id']) OR !isset($_GET['section_id']) OR !isset($_GET['order_id']) OR !is_numeric($_GET['page_id']) OR !is_numeric($_GET['section_id']) OR !is_numeric($_GET['order_id']) OR !isset($_SESSION['USER_ID']) OR !isset($_SESSION['GROUP_ID'])) {
	die($MESSAGE['FRONTEND']['SORRY_NO_VIEWING_PERMISSIONS']);
} else {
	$page_id = $_GET['page_id'];
	$section_id = $_GET['section_id'];
	$order_id = $_GET['order_id'];
}

// Check if user is authenticated to view this page
$admin = new admin('', '', false, false);
if ($admin->get_page_permission($page_id, $action='admin') === false) {
	// User allowed to view this page
	die($MESSAGE['ADMIN']['INSUFFICIENT_PRIVELLIGES']);
}



//Look for language File
if (LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/bakery/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
    }
}

// Header
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $MOD_BAKERY['TXT_ORDER']." ".$TEXT['VIEW_DETAILS']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php if (defined('DEFAULT_CHARSET')) { echo DEFAULT_CHARSET; } else { echo 'utf-8'; }?>" />
<link href="<?php echo WB_URL; ?>/modules/bakery/backend.css" rel="stylesheet" type="text/css" />
</head>

<?php
// Get invoice data string from db customer table
$query_customer = $database->query("SELECT invoice FROM ".TABLE_PREFIX."mod_bakery_customer WHERE order_id = '$order_id'");
if ($query_customer->numRows() > 0) {
	$customer = $query_customer->fetchRow();
	if ($customer['invoice'] != "") {
		// Convert string to array
		$invoice = stripslashes($customer['invoice']);
		$invoice_array = explode("&&&&&", $invoice);
		// Vars
		$order_id = $invoice_array[0];
		#$shop_name = $invoice_array[1];
		#$bank_account = $invoice_array[2];
		#$cust_name = $invoice_array[3];
		#$address = $invoice_array[4];
		$cust_address = $invoice_array[5];
		$ship_address = $invoice_array[6];
		#$cust_email = $invoice_array[7];
		$html_item_list = $invoice_array[8];
		$order_date = $invoice_array[9];

		// Change frontend classes (eg. mod_bakery_anything_f) to backend classes (eg. mod_bakery_anything_b)
		$html_item_list = str_replace("_f'", "_b'", $html_item_list);


// Show order
?>
<body>
<div id="order">
  <table width="540px" align="center" border="0" cellspacing="0" cellpadding="3">
	<tr>
	  <td colspan="6"><span class="mod_bakery_order_b"><?php echo $MOD_BAKERY['TXT_ORDER_ID']."</span>: ".$order_id; ?><br />
		<span class="mod_bakery_order_b"><?php echo $MOD_BAKERY['TXT_ORDER_DATE']."</span>: ".$order_date; ?></td>
	</tr>
	<tr>
	  <td colspan="6">
		<table width="98%" border="0" cellspacing="0" cellpadding="6">
		  <tr>
			<td valign="top" width="10%"><span class="mod_bakery_address_b"><?php echo $MOD_BAKERY['TXT_ADDRESS']; ?></span>:</td> 
			<td valign="top" width="30%"><?php echo $cust_address; ?></td>
			<td width="4%">&nbsp;</td>
			<td valign="top" width="10%"><span class="mod_bakery_address_b"><?php echo $MOD_BAKERY['TXT_SHIP_ADDRESS']; ?></span>:</td>
			<td valign="top"><?php echo $ship_address; ?></td>
		  </tr>
		</table></td>
	</tr>
    <tr>
	  <td colspan="6"><?php echo $html_item_list; ?></td>
	</tr>
	<tr id="button" valign="top">
	  <td colspan="3" height="30" align="left" style="padding-left: 12px;">&nbsp;</td>
	  <td colspan="3" height="30" align="right" style="padding-right: 12px;">
	    <input type="button" value="<?php echo $TEXT['CLOSE']; ?>" onclick="javascript: window.close();" style="width: 120px; margin-top: 5px;" />
	  </td>
	</tr>
  </table>
</div>

	<?php
	}
	else {
	echo "<p class='mod_bakery_error_b'>".$TEXT['NONE_FOUND']."!</p>";
	echo "<p style='text-align: right;'><input type='button' value='{$TEXT['CLOSE']}' onclick='javascript: window.close();' style='width: 120px; margin-top: 5px;' /></p>";
	}
}
?>

</body>
