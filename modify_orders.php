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


// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Make use of the skinable backend themes of WB > 2.7
// Check if THEME_URL is supported otherwise use ADMIN_URL
if (!defined('THEME_URL')) {
	define('THEME_URL', ADMIN_URL);
}

// Look for language file
if (LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/bakery/languages/EN.php');
    if (file_exists(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/bakery/languages/'.LANGUAGE.'.php');
    }
}

// Show current or archived orders
if (isset($_GET['view'])) {
	$view = $_GET['view'];
}
else {
	$view = 'current';
}

// Get the time until the admin will be alerted if the invoice has not been payed
$query_payment_methods = $database->query("SELECT value_2, value_3 FROM `".TABLE_PREFIX."mod_bakery_payment_methods` WHERE directory = 'invoice' LIMIT 1");
if ($query_payment_methods->numRows() > 0) {
	$payment_method = $query_payment_methods->fetchRow();
	$invoice_alert  = is_numeric($payment_method['value_2']) ? $payment_method['value_2'] : 0;
	$reminder_alert = is_numeric($payment_method['value_3']) ? $payment_method['value_3'] : 0;
}

// Make difference between current orders and archived orders
if ($view == 'current') {
	$toggle = 'archive';
	$toggle_page = $MOD_BAKERY['TXT_ORDER_ARCHIVED'];
	$current_page = $MOD_BAKERY['TXT_ORDER_CURRENT'];
	$query_customer = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer` WHERE status != 'archived' AND submitted != 'no' ORDER BY order_date DESC");
}
else {
	$toggle = 'current';
	$toggle_page = $MOD_BAKERY['TXT_ORDER_CURRENT'];
	$current_page = $MOD_BAKERY['TXT_ORDER_ARCHIVED'];
	$query_customer = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_bakery_customer` WHERE status = 'archived' AND submitted != 'no' ORDER BY order_date DESC");
}


echo "<h2>{$MOD_BAKERY['TXT_ORDER_ADMIN']}: <br/>$current_page <span style='text-transform: lowercase;'>{$TEXT['MODIFY']}/{$TEXT['DELETE']}</span></h2>"

// Show buttons
?>

<script type="text/javascript">

function newInvoice(url) {
  if (screen.availHeight) {
    var invoiceWindowHeight = screen.availHeight;
  }
  else {
    var invoiceWindowHeight = 800;
  }
  invoiceWindow = window.open(url + "#bottom", "", "width=750, height=" + invoiceWindowHeight + ", left=100, top=0, scrollbars=yes");
  invoiceWindow.focus();
}

function showOrder(url) {
  orderWindow = window.open(url, "", "width=600, height=500, left=150, top=100, scrollbars=yes");
  orderWindow.focus();
}

</script>

<table width="98%" align="center" cellpadding="0" cellspacing="0">
  <tr height="30" class="mod_bakery_submit_row_b">
	<td align="left" width="50%" style="padding-left: 12px;">
		<input type="button" value="<?php echo $toggle_page; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/bakery/modify_orders.php?page_id=<?php echo $page_id; ?>&view=<?php echo $toggle; ?>';" />
	</td>
	<td align="right" width="50%" style="padding-right: 12px;">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" />
	</td>
  </tr>
</table>
<br />
<?php


// Query customer table
if ($query_customer->numRows() > 0) {
	// Customer table header
	?>
	<form name="modify" action="<?php echo WB_URL; ?>/modules/bakery/save_orders.php" method="post" style="margin: 0;">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
	<table cellpadding="2" cellspacing="0" border="0" width="98%" align="center">
	<tr height="30" valign="bottom" class="mod_bakery_submit_row_b">
      <th colspan="2" align="left"><span style="margin-left: 5px;"><?php echo $MOD_BAKERY['TXT_ORDER']; ?></span></th>
      <th colspan="2" align="left"><?php echo $MOD_BAKERY['TXT_CUSTOMER']; ?></th>
      <th align="left"><?php echo $MOD_BAKERY['TXT_ORDER_DATE']; ?></th>
      <th colspan="2" align="left"><?php echo $MOD_BAKERY['TXT_STATUS']; ?></th>
	  <th colspan="3"><?php echo $TEXT['ACTIONS']; ?></th>
	</tr>
	<?php
	
	// List order table
	$row = 'a';
	while ($costumer = $query_customer->fetchRow()) {
		?>
		<tr class="row_<?php echo $row; ?>" height="20">
			<td width="30" align="right"><?php echo $costumer['order_id']; ?></td>
			<td width="30" align="left">
				<?php
				// Show payment method icons
				$payment_method = $costumer['submitted'];
				// Include payment method info file
				$info_file_path = WB_PATH."/modules/bakery/payment_methods/$payment_method/info.php";
				include($info_file_path);
				// Replace 'cod', 'advance' and 'invoice' by localisations
				$payment_method_name = $payment_method == "cod" ? $MOD_BAKERY['TXT_PAYMENT_METHOD_COD'] : $payment_method_name;
				$payment_method_name = $payment_method == "advance" ? $MOD_BAKERY['TXT_PAYMENT_METHOD_ADVANCE'] : $payment_method_name;
				$payment_method_name = $payment_method == "invoice" ? $MOD_BAKERY['TXT_PAYMENT_METHOD_INVOICE'] : $payment_method_name;
				// Show icon
				echo "<img src='".WB_URL."/modules/bakery/payment_methods/$payment_method/icon.png' alt='$payment_method_name' title='$payment_method_name' border='0' />";

			// Show email, customer name and order date ?>
			</td>
			<td width="18">
			<a href="mailto:<?php echo stripslashes($costumer['cust_email']); ?>"><img src="<?php echo WB_URL; ?>/modules/bakery/images/email.png" alt="<?php echo $TEXT['EMAIL']; ?>" title="<?php echo $TEXT['EMAIL']." ".$TEXT['TO']." ".stripslashes($costumer['cust_email']); ?>" style="margin-bottom: -3px;" border="0" /></a>
			</td>
			<td>
			<a href="<?php echo WB_URL; ?>/modules/bakery/view_order.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&order_id=<?php echo $costumer['order_id']; ?>" onclick="showOrder(this.href); return false;"><?php echo stripslashes($costumer['cust_first_name'])." ".stripslashes($costumer['cust_last_name']); ?></a>
			</td>
			<td width="135"><?php echo date(DATE_FORMAT.', '.TIME_FORMAT, $costumer['order_date']); ?></td>
			<td width="22">
			<?php
			
			// Show colored images for status
			$status_img_url = WB_URL."/modules/bakery/images/status";
			$status_img_style = "style='margin-bottom: -3px;' border='0'";
			switch (stripslashes($costumer['status'])) {

				case 'ordered': echo "<img src='$status_img_url/ordered.gif' alt='{$MOD_BAKERY['TXT_STATUS_ORDERED']}' title='{$MOD_BAKERY['TXT_STATUS_ORDERED']}' $status_img_style />"; break;

				case 'shipped': echo "<img src='$status_img_url/shipped.gif' alt='{$MOD_BAKERY['TXT_STATUS_SHIPPED']}' title='{$MOD_BAKERY['TXT_STATUS_SHIPPED']}' $status_img_style />"; break;

				case 'busy': echo "<img src='$status_img_url/busy.gif' alt='{$MOD_BAKERY['TXT_STATUS_BUSY']}' title='{$MOD_BAKERY['TXT_STATUS_BUSY']}' $status_img_style />"; break;

				case 'invoice':
					// Invoice alert
					if ($costumer['order_date'] + (60 * 60 * 24 * $invoice_alert) < time() && $invoice_alert != 0) {
						echo "<img src='$status_img_url/alert.gif' alt='{$MOD_BAKERY['TXT_STATUS_REMINDER']}' title='{$MOD_BAKERY['TXT_STATUS_REMINDER']}' $status_img_style />"; break;	
						}
					else {
						echo "<img src='$status_img_url/invoice.gif' alt='{$MOD_BAKERY['TXT_STATUS_INVOICE']}' title='{$MOD_BAKERY['TXT_STATUS_INVOICE']}' $status_img_style />"; break;
					}

				case 'reminder':
					// Reminder alert
					if ($costumer['order_date'] + (60 * 60 * 24 * $reminder_alert) < time() && $reminder_alert != 0) {
						echo "<img src='$status_img_url/alert.gif' alt='{$MOD_BAKERY['TXT_STATUS_REMINDER']}' title='{$MOD_BAKERY['TXT_STATUS_REMINDER']}' $status_img_style />"; break;	
						}
					else {
						echo "<img src='$status_img_url/reminder.gif' alt='{$MOD_BAKERY['TXT_STATUS_REMINDER']}' title='{$MOD_BAKERY['TXT_STATUS_REMINDER']}' $status_img_style />"; break;
					}

				case 'paid': echo "<img src='$status_img_url/paid.gif' alt='{$MOD_BAKERY['TXT_STATUS_PAID']}' title='{$MOD_BAKERY['TXT_STATUS_PAID']}' $status_img_style />"; break;

				case 'archived': echo "<img src='$status_img_url/archived.gif' alt='{$MOD_BAKERY['TXT_STATUS_ARCHIVED']}' title='{$MOD_BAKERY['TXT_STATUS_ARCHIVED']}' $status_img_style />"; break;
			}
			echo "</td>\n<td width='120'>";
			
// Show status select depending on the payment method
if (stripslashes($costumer['status']) == "archived") {
	echo " {$MOD_BAKERY['TXT_STATUS_ARCHIVED']}";
} else {
	switch (stripslashes($costumer['submitted'])) {
		case 'advance': $select_status = array("ordered" => $MOD_BAKERY['TXT_STATUS_ORDERED'], "paid" => $MOD_BAKERY['TXT_STATUS_PAID'], "shipped" => $MOD_BAKERY['TXT_STATUS_SHIPPED'], "archived" => $MOD_BAKERY['TXT_STATUS_ARCHIVE']);
			break;
		case 'invoice': $select_status = array("ordered" => $MOD_BAKERY['TXT_STATUS_ORDERED'], "shipped" => $MOD_BAKERY['TXT_STATUS_SHIPPED'], "invoice" => $MOD_BAKERY['TXT_STATUS_INVOICE'], "reminder" => $MOD_BAKERY['TXT_STATUS_REMINDER'], "paid" => $MOD_BAKERY['TXT_STATUS_PAID'], "archived" => $MOD_BAKERY['TXT_STATUS_ARCHIVE']);
			break;
		default: $select_status = array("ordered" => $MOD_BAKERY['TXT_STATUS_ORDERED'], "shipped" => $MOD_BAKERY['TXT_STATUS_SHIPPED'], "archived" => $MOD_BAKERY['TXT_STATUS_ARCHIVE']);
	}
	// Generate status select
	echo " <select name='status[{$costumer['order_id']}]' style='width: 110px'>";
	foreach ($select_status as $option_value => $option_text) {
		echo "<option value='$option_value'";
		echo stripslashes($costumer['status']) == $option_value ? " selected='selected'" : "";
		echo ">$option_text</option>\n";
	}
	echo "</select>";
}
?>
			
			</td>
			<td  width="22">
			  <a href="<?php echo WB_URL; ?>/modules/bakery/view_invoice.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&order_id=<?php echo $costumer['order_id']; ?>" onclick="newInvoice(this.href); return false;"><img src="<?php echo WB_URL; ?>/modules/bakery/images/print.gif" alt="<?php echo $MOD_BAKERY['TXT_PRINT_INVOICE']; ?>" title="<?php echo $MOD_BAKERY['TXT_PRINT_INVOICE']; ?>" border="0" /></a>
			</td>
			<td  width="22">
				<a href="<?php echo WB_URL; ?>/modules/bakery/view_order.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&order_id=<?php echo $costumer['order_id']; ?>" onclick="showOrder(this.href); return false;" title="<?php echo $TEXT['VIEW_DETAILS']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/view_16.png" alt="<?php echo $MOD_BAKERY['TXT_INVOICE']." ".$TEXT['VIEW_DETAILS']; ?>" border="0" />
				</a>
			</td>
			<td width="22">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/bakery/delete_order.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&order_id=<?php echo $costumer['order_id']; ?>&view=<?php echo $view; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/delete_16.png" border="0" alt="<?php echo $TEXT['DELETE']; ?>" />
				</a>
			</td>
		</tr>
		<?php
		// Alternate row color
		if ($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	?>
	</table>
	<?php
} else {
	echo $TEXT['NONE_FOUND']."<br /><br />";
}

// Show buttons if view is current
if ($view == 'current') {
	?>
	<table width="98%" align="center" cellpadding="0" cellspacing="0" class="mod_bakery_submit_row_b">
		<tr valign="top">
		  <td height="30" align="left"  style="padding-left: 12px;">
		  <input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" /></td>
		  <td height="30" align="right"  style="padding-right: 12px;">
		  <input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" /></td>
		</tr>
	</table>
	</form>
	<?php
}

// Print admin footer
$admin->print_footer();

?>
