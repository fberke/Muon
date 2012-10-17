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
 


// Include WB template parser and create template object
require_once(WB_PATH.'/include/phplib/template.inc');
$tpl = new Template(WB_PATH.'/modules/bakery/templates/confirmation');
// Define how to deal with unknown {PLACEHOLDERS} (remove:=default, keep, comment)
$tpl->set_unknowns('remove');
// Define debug mode (0:=disabled (default), 1:=variable assignments, 2:=calls to get variable, 4:=debug internals)
$tpl->debug = 0;


// Check if payment status and payment method is set
if (is_string($payment_status) && is_string($payment_method)) {

	// Look for payment method language file
	if (LANGUAGE_LOADED) {
		include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/EN.php');
		if (file_exists(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php')) {
			include(WB_PATH.'/modules/bakery/payment_methods/'.$payment_method.'/languages/'.LANGUAGE.'.php');
		}
	}
	
/*	
	// Get continue url
	$query_continue_url = $database->query("SELECT p.link FROM ".TABLE_PREFIX."pages p INNER JOIN ".TABLE_PREFIX."mod_bakery_page_settings ps ON p.page_id = ps.page_id WHERE p.page_id = ps.continue_url AND ps.section_id = '$section_id'");
	if ($query_continue_url->numRows() > 0) {
		$fetch_continue_url = $query_continue_url->fetchRow();
		$setting_continue_url = WB_URL.PAGES_DIRECTORY.stripslashes($fetch_continue_url['link']).PAGE_EXTENSION;
	}
*/

	// ERROR
	// *****

	if ($payment_status == "error") {
		
		// Assign page filename and pagetitle for web analytics
		global $bakery_analytics;
		$bakery_analytics ['filename'] = str_replace(PAGE_EXTENSION,"",$setting_continue_url).$MOD_BAKERY_FILENAME ['payment_error'];
		$bakery_analytics ['pagetitle'] = $MOD_BAKERY_PAGETITLE ['payment_error'];

		// Show error message using template file
		$tpl->set_file('error', 'error.htm');
		$tpl->set_var(array(
			'ERROR'			=>	$MOD_BAKERY[$payment_method]['ERROR'],
			'SETTING_CONTINUE_URL'	=>	$setting_continue_url,
			'TXT_CONTINUE_SHOPPING'	=>	$MOD_BAKERY['TXT_CONTINUE_SHOPPING'],
			'TXT_QUIT_ORDER'	=>	$MOD_BAKERY['TXT_QUIT_ORDER'],
			'TXT_JS_CONFIRM'	=>	$MOD_BAKERY['TXT_JS_CONFIRM'],
			'ANALYTICS_FILENAME'	=>	$bakery_analytics ['filename'],
			'ANALYTICS_PAGETITLE'	=>	$bakery_analytics ['pagetitle']
		));
		$tpl->pparse('output', 'error');
		return;
	}



	// CANCELED
	// ********

	if ($payment_status == "canceled") {
		
		// Assign page filename and pagetitle for web analytics
		global $bakery_analytics;
		$bakery_analytics ['filename'] = str_replace(PAGE_EXTENSION,"",$setting_continue_url).$MOD_BAKERY_FILENAME ['payment_cancelled'];
		$bakery_analytics ['pagetitle'] = $MOD_BAKERY_PAGETITLE ['payment_cancelled'];

		// Show message using template file
		$tpl->set_file('canceled', 'canceled.htm');
		$tpl->set_var(array(
			'CANCELED'		=>	$MOD_BAKERY[$payment_method]['CANCELED'],
			'SETTING_CONTINUE_URL'	=>	$setting_continue_url,
			'TXT_CONTINUE_SHOPPING'	=>	$MOD_BAKERY['TXT_CONTINUE_SHOPPING'],
			'TXT_QUIT_ORDER'	=>	$MOD_BAKERY['TXT_QUIT_ORDER'],
			'TXT_JS_CONFIRM'	=>	$MOD_BAKERY['TXT_JS_CONFIRM'],
			'ANALYTICS_FILENAME'	=>	$bakery_analytics ['filename'],
			'ANALYTICS_PAGETITLE'	=>	$bakery_analytics ['pagetitle']
		));
		$tpl->pparse('output', 'canceled');
		return;
	}



	// SUCCESS OR PENDING
	// ******************

	if ($payment_status == "success" || $payment_status == "pending") {
		
		// Assign page filename and pagetitle for web analytics
		global $bakery_analytics;
		$bakery_analytics ['filename'] = str_replace(PAGE_EXTENSION,"",$setting_continue_url).$MOD_BAKERY_FILENAME ['payment_success'];
		$bakery_analytics ['pagetitle'] = $MOD_BAKERY_PAGETITLE ['payment_success'];

		// Get the order id from the session var or,
		// in case this script has been called by a payment method directly (eg. paypal ipn),
		// use the one provided by the payment gateway
		$order_id = isset($order_id) && is_numeric($order_id) ? $order_id : $_SESSION['bakery']['order_id'];


		// EMAIL

		// In case the email has been sent before (eg. in the background by paypal ipn)
		// keep 'email_sent = true' to prevent sending emails twice
		$email_sent = isset($email_sent) && $email_sent ? true : false;

		// Send confirmation emails only if not sent before
		if ($email_sent === false) {
	
			// Get the email templates from the db
			$query_payment_methods = $database->query("SELECT cust_email_subject, cust_email_body, shop_email_subject, shop_email_body FROM ".TABLE_PREFIX."mod_bakery_payment_methods WHERE directory = '$payment_method'");
			if ($query_payment_methods->numRows() > 0) {
				$payment_methods = $query_payment_methods->fetchRow();
				$cust_email_subject = stripslashes($payment_methods['cust_email_subject']);
				$cust_email_body = stripslashes($payment_methods['cust_email_body']);
				$shop_email_subject = stripslashes($payment_methods['shop_email_subject']);
				$shop_email_body = stripslashes($payment_methods['shop_email_body']);
			}
	
			// Get email data string from db customer table
			$query_customer = $database->query("SELECT invoice FROM ".TABLE_PREFIX."mod_bakery_customer WHERE order_id = '$order_id'");
			if ($query_customer->numRows() > 0) {
				$customer = $query_customer->fetchRow();
				if ($customer['invoice'] != '') {
					// Convert string to array
					$invoice = stripslashes($customer['invoice']);
					$invoice_array = explode('&&&&&', $invoice);
	
					// Email vars to replace placeholders in the email body
					$setting_shop_name = $invoice_array[1];
					$bank_account = $invoice_array[2];
					$cust_name = $invoice_array[3];
					$cust_email = $invoice_array[7];
					$shop_email = $invoice_array[10];
					$address = $invoice_array[11];
					$cust_address = $invoice_array[12];
					$ship_address = $invoice_array[13];
					$item_list = $invoice_array[14];
				}
			}
			
			// add tabs to bank account to achieve better email structure
			$bank_account = "\t".str_replace ("\n", "\n\t", $bank_account);
			
			// In case this script has been called by a payment method directly (eg. paypal ipn)
			// we have to add the shop email var
			$setting_shop_email = isset($setting_shop_email) ? $setting_shop_email : $shop_email;

			// Make email headers
			if (defined('DEFAULT_CHARSET')) { $charset = DEFAULT_CHARSET; } else {  $charset = 'utf-8'; }
			//create a boundary string. It must be unique
			//so we use the MD5 algorithm to generate a random hash
			$random_hash = md5(date('r', time())); 
			$cust_header = "MIME-Version: 1.0"."\n";
			$cust_header .= "Content-Type: multipart/mixed; boundary=\"$random_hash\""."\n";
			
			$shop_header  = "MIME-Version: 1.0"."\n";
			$shop_header .= "Content-type: text/plain; charset=\"$charset\""."\n";
	
			$cust_email_headers  = $cust_header."Return-Path: $setting_shop_email"."\n";
			$cust_email_headers .= "Reply-To: $setting_shop_email"."\n";
			$cust_email_headers .= "From: $setting_shop_name <$setting_shop_email>"."\n";
			// $cust_email_headers .= "Bcc: ".$setting_shop_email;
	
			$shop_email_headers  = $shop_header."Return-Path: $setting_shop_email"."\n";
			$shop_email_headers .= "Reply-To: $cust_email"."\n";
			$shop_email_headers .= "From: $setting_shop_name <$setting_shop_email>";
	
			// Make transaction status notice
			$transaction_status_notice = '';
			if ($payment_status == 'pending' && isset($MOD_BAKERY[$payment_method]['TXT_TRANSACTION_STATUS'])) {
				$transaction_status_notice  = $MOD_BAKERY[$payment_method]['TXT_TRANSACTION_STATUS'];
			}
	
			// Replace placeholders by values in the email body 
			$vars = array('[ORDER_ID]', '[SHOP_NAME]', '[BANK_ACCOUNT]', '[TRANSACTION_STATUS]', '[CUSTOMER_NAME]', '[ADDRESS]', '[CUST_ADDRESS]', '[SHIPPING_ADDRESS]', '[CUST_EMAIL]', '[ITEM_LIST]');
			$values = array($order_id, $setting_shop_name, $bank_account, $transaction_status_notice, $cust_name, $address, $cust_address, $ship_address, $cust_email, $item_list);
		
			$cust_email_body_boundary = "--".$random_hash."\n";
			$cust_email_body_boundary .= "Content-Type: text/plain; charset=\"$charset\""."\n";
			$cust_email_body_boundary .= "Content-Transfer-Encoding: 8bit"."\n";
			
			$cust_email_att_boundary = "\n"."--".$random_hash."\n";
			$cust_email_att_boundary .= "Content-Type: application/pdf; name=NAME"."\n";
			$cust_email_att_boundary .= "Content-Disposition: attachment; filename=NAME"."\n";
			$cust_email_att_boundary .= "Content-Transfer-Encoding: base64"."\n"."\n";
						
			$attachment = "";
			
			// use German file names in Germany, otherwise English file names
			$tac_filename = (LANGUAGE == "DE") ? "agb.pdf" : "tac.pdf";
			$cancellation_filename = (LANGUAGE == "DE") ? "widerrufsbelehrung.pdf" : "cancellation.pdf";
			$privacy_filename = (LANGUAGE == "DE") ? "datenschutz.pdf" : "privacy.pdf";
						
			// if corresponding URL is set in General Settings and file exists, attach the PDF
			if ((!empty($setting_tac_url)) && file_exists(WB_URL.'/media/bakery/documents/'.$tac_filename)) {
				$attachment .= str_replace ("NAME", $tac_filename, $cust_email_att_boundary);
				$attachment .= chunk_split(base64_encode(file_get_contents(WB_URL.'/media/bakery/documents/'.$tac_filename))); 
			}
			if ((!empty($setting_cancellation_url)) && file_exists(WB_URL.'/media/bakery/documents/'.$cancellation_filename)) {
				$attachment .= str_replace ("NAME", $cancellation_filename, $cust_email_att_boundary);
				$attachment .= chunk_split(base64_encode(file_get_contents(WB_URL.'/media/bakery/documents/'.$cancellation_filename)));
			}
			if ((!empty($setting_privacy_url)) && file_exists(WB_URL.'/media/bakery/documents/'.$privacy_filename)) {
				$attachment .= str_replace ("NAME", $privacy_filename, $cust_email_att_boundary);
				$attachment .= chunk_split(base64_encode(file_get_contents(WB_URL.'/media/bakery/documents/'.$privacy_filename)));
			}
			
			$cust_email_subject = str_replace($vars, $values, $cust_email_subject);
			$cust_email_body    = str_replace($vars, $values, $cust_email_body);
						
			$shop_email_subject = str_replace($vars, $values, $shop_email_subject);
			$shop_email_body    = str_replace($vars, $values, $shop_email_body);
	
			// Clean output - remove all "\r" in emails to avoid double line breaks
			$cust_email_subject = str_replace ("\r", '', $cust_email_subject);
			$cust_email_body    = str_replace ("\r", '', $cust_email_body);
			$shop_email_subject = str_replace ("\r", '', $shop_email_subject);
			$shop_email_body    = str_replace ("\r", '', $shop_email_body);
	
			// Create body with attechments and boundaries
			$cust_email_body_full = $cust_email_body_boundary."\n";
			$cust_email_body_full .= $cust_email_body."\n";
			$cust_email_body_full .= $attachment."\n";
	
			// Send confirmation e-mail to customer and shop
			$cust_email_sent = (mail($cust_email, $cust_email_subject, $cust_email_body_full, $cust_email_headers));

			$shop_email_sent = (mail($setting_shop_email, $shop_email_subject, $shop_email_body, $shop_email_headers));
			
		}


		// WEBSITE CONFIRMATION

		// In case payment data has been transfered in the background (eg. paypal ipn)
		// there is no way to show a confirmation page to the customer
		if (!isset($no_confirmation)) {

			// Show confirmation using template file
			if ($payment_status == "success") {
				$tpl->set_file('success', 'success.htm');
				$tpl->set_var(array(
					'TXT_SUCCESS'		=>	$MOD_BAKERY[$payment_method]['TXT_SUCCESS'],
					'TXT_SHIPMENT'		=>	$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'],
					'TXT_THANK_U_ORDER'	=>	$MOD_BAKERY['TXT_THANK_U_ORDER'],
					'SETTING_CONTINUE_URL'	=>	$setting_continue_url,
					'TXT_BACK_TO_SHOP'	=>	$MOD_BAKERY['TXT_BACK_TO_SHOP'],
					'ANALYTICS_FILENAME'	=>	$bakery_analytics ['filename'],
					'ANALYTICS_PAGETITLE'	=>	$bakery_analytics ['pagetitle']
				));
				$tpl->pparse('output', 'success');
			}
			elseif ($payment_status == "pending") {
				$tpl->set_file('pending', 'pending.htm');
				$tpl->set_var(array(
					'TXT_PENDING'		=>	$MOD_BAKERY[$payment_method]['TXT_PENDING'],
					'TXT_SHIPMENT'		=>	$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'],
					'TXT_THANK_U_ORDER'	=>	$MOD_BAKERY['TXT_THANK_U_ORDER'],
					'SETTING_CONTINUE_URL'	=>	$setting_continue_url,
					'TXT_BACK_TO_SHOP'	=>	$MOD_BAKERY['TXT_BACK_TO_SHOP'],
					'ANALYTICS_FILENAME'	=>	$bakery_analytics ['filename'],
					'ANALYTICS_PAGETITLE'	=>	$bakery_analytics ['pagetitle']
				));
				$tpl->pparse('output', 'pending');
			}
	
			// If emails have not been sent show additional email error using template file
			if ($cust_email_sent === false) {
				$cust_email_link = '<a href="mailto:' . $cust_email . '">' . $cust_email . '<a>';
				$tpl->set_file('email_error', 'email_error.htm');
				$tpl->set_var(array(
					'ERR_EMAIL_NOT_SENT'	=>	$MOD_BAKERY['ERR_EMAIL_NOT_SENT'] . ':<br />' . $cust_email_link
				));
				$tpl->pparse('output', 'email_error');
			}
			
			if ($shop_email_sent === false) {
				$shop_email_link = '<a href="mailto:' . $setting_shop_email . '">' . $setting_shop_email . '<a>';
				$tpl->set_file('email_error', 'email_error.htm');
				$tpl->set_var(array(
					'ERR_EMAIL_NOT_SENT'	=>	$MOD_BAKERY['ERR_EMAIL_NOT_SENT'] . ':<br />' . $shop_email_link
				));
				$tpl->pparse('output', 'email_error');
			}
		}


		// Update db
		if ($payment_method == "pickup") {
			$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_customer SET shipping_fee = '0', submitted = '$payment_method', status = 'ordered' WHERE order_id='$order_id'");
		} else {
			$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_customer SET submitted = '$payment_method', status = 'ordered' WHERE order_id='$order_id'");
		}

		// Clean up the session array
		if (isset($_SESSION['bakery'])) {
			unset($_SESSION['bakery']);
		}
		return;
	}
} else {
	echo '<strong>ERROR: Payment status or payment method is not defined.</strong>';
	return;
}
?>