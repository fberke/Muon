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
$tpl = new Template(WB_PATH.'/modules/bakery/templates/form');
// Define how to deal with unknown {PLACEHOLDERS} (remove:=default, keep, comment)
$tpl->set_unknowns('remove');
// Define debug mode (0:=disabled (default), 1:=variable assignments, 2:=calls to get variable, 4:=debug internals)
$tpl->debug = 0;

// Include country file depending on the language
if (LANGUAGE_LOADED) {
	if (file_exists(WB_PATH.'/modules/bakery/languages/countries/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/bakery/languages/countries/'.LANGUAGE.'.php');
	}
}
else {
	require_once(WB_PATH.'/modules/bakery/languages/countries/EN.php');
}

// Include state file depending on the shop country
$select_shop_country = '';
$use_states = false;
if (file_exists(WB_PATH.'/modules/bakery/languages/states/'.$setting_shop_country.'.php')) {
	require_once(WB_PATH.'/modules/bakery/languages/states/'.$setting_shop_country.'.php');
	$select_shop_country = $setting_shop_country;
	$use_states = true;
}



// GET CUSTOMER DATA TO PREPOPULATE THE TEXT FIELDS
// ************************************************

// Make arrays for all forms and fields
$forms = array('cust', 'ship');
$fields = array('first_name', 'last_name', 'street', 'city', 'state', 'country', 'zip', 'email', 'confirm_email', 'phone');

// Loop through post vars and import them into session var and the current symbol table
foreach ($forms as $form) {
	foreach ($fields as $field) {
		$field_var = $form.'_'.$field;
		if (!isset($_SESSION['bakery'][$form][$field])) $_SESSION['bakery'][$form][$field] = "";
		if (isset($_POST[$field_var])) $_SESSION['bakery'][$form][$field] = strip_tags($_POST[$field_var]);
		$$field_var = $_SESSION['bakery'][$form][$field];
	}
}

// For the first time try to get customer data of a previous order from the db...
if (isset($_SESSION['USER_ID']) && $cust_first_name == "" && $cust_last_name == "" && $cust_street == "" && $cust_city == "" && $cust_state == "" && $cust_zip == "" && $cust_email == "" && $cust_phone == "") {
	$sql_result = $database->query(
		"SELECT
		cust_first_name,
		cust_last_name, 
		cust_street,
		cust_city,
		cust_state,
		cust_country,
		cust_zip,
		cust_email,
		cust_phone
		FROM " .TABLE_PREFIX."mod_bakery_customer
		WHERE user_id = '{$_SESSION['USER_ID']}'
		ORDER BY order_id DESC LIMIT 1"
		);
	$n = $sql_result->numRows();
	if ($n > 0) {
		$row = $sql_result->fetchRow();
		extract($row);
		$cust_confirm_email = $cust_email;
	}
}

// ...and same for the shipping data
if (isset($_SESSION['USER_ID']) &&  $ship_first_name == "" && $ship_last_name == "" && $ship_street == "" && $ship_city == "" && $ship_state == "" && $ship_zip == "") {
	$sql_result = $database->query(
		"SELECT 
		ship_first_name,
		ship_last_name,
		ship_street,
		ship_city,
		ship_state,
		ship_country,
		ship_zip 
		FROM " .TABLE_PREFIX."mod_bakery_customer
		WHERE user_id = '{$_SESSION['USER_ID']}'
		ORDER BY order_id DESC LIMIT 1"
		);
	$n = $sql_result->numRows();
	if ($n > 0) {
		$row = $sql_result->fetchRow();
		extract($row);
	}
}

// If no country has been selected, preselect the shop country
if (!isset($cust_country) || $cust_country == '') {
	$cust_country = $setting_shop_country;
}
if ((!isset($ship_country) || $ship_country == '') && $setting_shipping_form != 'none') {
	$ship_country = $setting_shop_country;
}
// If no state is selected, preselect the shop state
if (!isset($cust_state) || $cust_state == '') {
	$cust_state = $setting_shop_state;
}
if ((!isset($ship_state) || $ship_state == '') && $setting_shipping_form != 'none') {
	$ship_state = $setting_shop_state;
}



// SHOW TITLE AND MESSAGES IF ANY
// ******************************

// Assign page filename and pagetitle for web analytics
global $bakery_analytics;
$bakery_analytics ['filename'] = str_replace(PAGE_EXTENSION,"",$setting_continue_url).$MOD_BAKERY_FILENAME ['address_form'];
$bakery_analytics ['pagetitle'] = $MOD_BAKERY_PAGETITLE ['address_form'];

// Show form title using template file
$tpl->set_file('form_title', 'title.htm');
$tpl->set_var(array(
	'WB_URL'		=>	WB_URL,
	'STEP_IMG_DIR'		=>	$step_img_dir,
	'TXT_SUBMIT_ORDER'	=>	$MOD_BAKERY['TXT_SUBMIT_ORDER'],
	'TXT_ADDRESS'		=>	$MOD_BAKERY['TXT_ADDRESS']
));
$tpl->pparse('output', 'form_title');

// Show form error messages using template file
if (isset($form_error)) {
	$tpl->set_file('form_error', 'error.htm');
	$tpl->set_var(array(
		'FORM_ERROR'	=>	$form_error
	));
	$tpl->pparse('output', 'form_error');
}

// Open form using template file
// This prevents having a possible error message within the list-styled form,
// which is semantically incorrect
$tpl->set_file('form_open', 'open.htm');
$tpl->set_var(array(
	'TXT_FILL_IN_ADDRESS'	=>	$MOD_BAKERY['TXT_FILL_IN_ADDRESS'],
	'SETTING_CONTINUE_URL'	=>	$setting_continue_url
));
$tpl->pparse('output', 'form_open');

// SET FILE AND BLOCKS FOR FORM TEMPLATE
// *************************************

$tpl->set_file('form', 'form.htm');

$tpl->set_block('form', 'main_block', 'main');

$tpl->set_block('main_block', 'cust_country_block', 'cust_country');
$tpl->set_block('main_block', 'cust_state_block', 'cust_state');
$tpl->set_block('main_block', 'cust_textfields_block', 'cust_textfields');
$tpl->set_block('main_block', 'cust_button_block', 'cust_button');
$tpl->set_block('main_block', 'cust_buttons_block', 'cust_buttons');

$tpl->set_block('main_block', 'ship_title_block', 'ship_title');
$tpl->set_block('main_block', 'ship_country_block', 'ship_country');
$tpl->set_block('main_block', 'ship_state_block', 'ship_state');
$tpl->set_block('main_block', 'ship_textfields_block', 'ship_textfields');
$tpl->set_block('main_block', 'ship_button_block', 'ship_button');
$tpl->set_block('main_block', 'ship_buttons_block', 'ship_buttons');

// IMPORTANT NOTICE
// ****************
// If you require less fields for your shop, just remove them from the arrays

// CUSTOMER ADDRESS FORM ONLY
// **************************

// Make array for the customer address form with state field
if ($setting_state_field == "show") {
	if ($setting_zip_location == "end") {
		// Show zip at the end of address
		$cust_info = array(
			"cust_email" => $MOD_BAKERY['TXT_CUST_EMAIL'], 
			"cust_confirm_email" => $MOD_BAKERY['TXT_CUST_CONFIRM_EMAIL'], 
			"cust_first_name" => $MOD_BAKERY['TXT_CUST_FIRST_NAME'], 
			"cust_last_name" => $MOD_BAKERY['TXT_CUST_LAST_NAME'], 
			"cust_street" => $MOD_BAKERY['TXT_CUST_ADDRESS'], 
			"cust_city" => $MOD_BAKERY['TXT_CUST_CITY'], 
			"cust_state" => $MOD_BAKERY['TXT_CUST_STATE'], 
			"cust_zip" => $MOD_BAKERY['TXT_CUST_ZIP'], 
			"cust_country" => $MOD_BAKERY['TXT_CUST_COUNTRY'], 
			"cust_phone" => $MOD_BAKERY['TXT_CUST_PHONE']
			);
		$length = array(
			"cust_email" => "50", 
			"cust_confirm_email" => "50", 
			"cust_first_name" => "50", 
			"cust_last_name" => "50", 
			"cust_street" => "50", 
			"cust_zip" => "10", 
			"cust_city" => "50", 
			"cust_state" => "50", 
			"cust_phone" => "20"
			);
	} else {
		// Show zip inside of address
		$cust_info = array(
			"cust_email" => $MOD_BAKERY['TXT_CUST_EMAIL'], 
			"cust_confirm_email" => $MOD_BAKERY['TXT_CUST_CONFIRM_EMAIL'],
			"cust_first_name" => $MOD_BAKERY['TXT_CUST_FIRST_NAME'],
			"cust_last_name" => $MOD_BAKERY['TXT_CUST_LAST_NAME'], 
			"cust_street" => $MOD_BAKERY['TXT_CUST_ADDRESS'],
			"cust_zip" => $MOD_BAKERY['TXT_CUST_ZIP'],
			"cust_city" => $MOD_BAKERY['TXT_CUST_CITY'],
			"cust_state" => $MOD_BAKERY['TXT_CUST_STATE'], 
			"cust_country" => $MOD_BAKERY['TXT_CUST_COUNTRY'],
			"cust_phone" => $MOD_BAKERY['TXT_CUST_PHONE']
			);
		$length = array(
			"cust_email" => "50", 
			"cust_confirm_email" => "50", 
			"cust_first_name" => "50", 
			"cust_last_name" => "50",
			"cust_street" => "50", 
			"cust_zip" => "10", 
			"cust_city" => "50",
			"cust_state" => "50", 
			"cust_phone" => "20"
			);
	}
}
// Make array for the customer address form w/o state field	
else {
	if ($setting_zip_location == "end") {
		// Show zip at the end of address
		$cust_info = array(
			"cust_email" => $MOD_BAKERY['TXT_CUST_EMAIL'],
			"cust_confirm_email" => $MOD_BAKERY['TXT_CUST_CONFIRM_EMAIL'], 
			"cust_first_name" => $MOD_BAKERY['TXT_CUST_FIRST_NAME'], 
			"cust_last_name" => $MOD_BAKERY['TXT_CUST_LAST_NAME'], 
			"cust_street" => $MOD_BAKERY['TXT_CUST_ADDRESS'],
			"cust_city" => $MOD_BAKERY['TXT_CUST_CITY'],
			"cust_zip" => $MOD_BAKERY['TXT_CUST_ZIP'], 
			"cust_country" => $MOD_BAKERY['TXT_CUST_COUNTRY'],
			"cust_phone" => $MOD_BAKERY['TXT_CUST_PHONE']
			);
		$length = array("cust_email" => "50", 
			"cust_confirm_email" => "50", 
			"cust_first_name" => "50", 
			"cust_last_name" => "50", 
			"cust_street" => "50", 
			"cust_zip" => "10", 
			"cust_city" => "50", 
			"cust_phone" => "20"
			);
	} else {	
		// Show zip inside of address
		$cust_info = array(
			"cust_email" => $MOD_BAKERY['TXT_CUST_EMAIL'],
			"cust_confirm_email" => $MOD_BAKERY['TXT_CUST_CONFIRM_EMAIL'],
			"cust_first_name" => $MOD_BAKERY['TXT_CUST_FIRST_NAME'], 
			"cust_last_name" => $MOD_BAKERY['TXT_CUST_LAST_NAME'],
			"cust_street" => $MOD_BAKERY['TXT_CUST_ADDRESS'], 
			"cust_zip" => $MOD_BAKERY['TXT_CUST_ZIP'], 
			"cust_city" => $MOD_BAKERY['TXT_CUST_CITY'], 
			"cust_country" => $MOD_BAKERY['TXT_CUST_COUNTRY'], 
			"cust_phone" => $MOD_BAKERY['TXT_CUST_PHONE']
			);
		$length = array(
			"cust_email" => "50",
			"cust_confirm_email" => "50", 
			"cust_first_name" => "50", 
			"cust_last_name" => "50", 
			"cust_street" => "50",
			"cust_zip" => "10",
			"cust_city" => "50", 
			"cust_phone" => "20"
			);
	}
}

// Initialize vars
$country_options = "";
$state_options = "";

// Loop through all fields and generate the form
foreach ($cust_info as $field => $value) {

	// Generate country dropdown menu...
	if ($field == "cust_country") {
		// Prepare cust country options
		for ($n = 1; $n <= count($MOD_BAKERY['TXT_COUNTRY_NAME']); $n++) {
			$country = $MOD_BAKERY['TXT_COUNTRY_NAME'][$n];
			$country_code = $MOD_BAKERY['TXT_COUNTRY_CODE'][$n];
			$selected_country = ($country_code == @$_POST['country'] || $country_code == @$cust_country) ? " selected='selected'" : "";
			$country_options .= "\n\t\t\t<option value='$country_code'$selected_country>$country</option>";
		}
		// Show cust country block using template file
		$tpl->set_var(array(
			'TXT_CUST_COUNTRY'	=>	$MOD_BAKERY['TXT_CUST_COUNTRY'],
			'SELECT_SHOP_COUNTRY'	=>	$select_shop_country,
			'COUNTRY_OPTIONS'	=>	$country_options
		));
		$tpl->parse('form', 'cust_country_block', true);
	}

	else {
		// Generate state dropdown menu...
		if ($use_states && $field == "cust_state") {
			// Prepare cust state options
			for ($n = 1; $n <= count($MOD_BAKERY['TXT_STATE_NAME']); $n++) {
				$state = $MOD_BAKERY['TXT_STATE_NAME'][$n];
				$state_code = $MOD_BAKERY['TXT_STATE_CODE'][$n];
				$selected_state = ($state_code == @$_POST['cust_state'] || $state_code == @$cust_state) ? " selected='selected'" : "";
				$state_options .= "\n\t\t\t<option value='$state_code'$selected_state>$state</option>";
			}
			// Show cust state options block using template file
			$tpl->set_var(array(
				'TXT_CUST_STATE'	=>	$MOD_BAKERY['TXT_CUST_STATE'],
				'STATE_OPTIONS'		=>	$state_options
			));
			$tpl->parse('form', 'cust_state_block', true);
		}

		// Generate all other fields
		// Add css class (red background) if the textfield is blank or incorrect
		$css_error_class = isset($error_bg) && in_array($field, $error_bg) ? 'mod_bakery_errorbg_f ' : '';
		// Show cust textfields block using template file
		$tpl->set_var(array(
			'TR_ID'			=>	$field."_text",
			'LABEL'			=>	$value,
			'CSS_ERROR_CLASS'	=>	$css_error_class,
			'NAME'			=>	$field,
			'VALUE'			=>	htmlspecialchars(@$$field, ENT_QUOTES),
			'MAXLENGHT'		=>	$length[$field]
		));
		$tpl->parse('form', 'cust_textfields_block', true);
	}
}

// Show the submit button (without shipping address form)...
if ($setting_shipping_form == "none") {
	$tpl->set_var(array(
		'TXT_BACK_TO_CART'	=>	$MOD_BAKERY['TXT_BACK_TO_CART'],
		'TXT_SUBMIT_ORDER'	=>	$MOD_BAKERY['TXT_SUBMIT_ORDER']
	));
	$tpl->parse('form', 'cust_button_block', true);
}
// ...or show a button to add the shipping address form and the submit button (without shipping address form)
elseif (!isset($_SESSION['bakery']['ship_form'])) {
	$tpl->set_var(array(
		'TXT_ADD_SHIP_FORM'	=>	$MOD_BAKERY['TXT_ADD_SHIP_FORM'],
		'TXT_BACK_TO_CART'	=>	$MOD_BAKERY['TXT_BACK_TO_CART'],
		'TXT_SUBMIT_ORDER'	=>	$MOD_BAKERY['TXT_SUBMIT_ORDER']
	));
	$tpl->parse('form', 'cust_buttons_block', true);
}



// ADD SHIPPING ADDRESS FORM IF REQUIRED
// *************************************

// Comment out those fields you don't need. See hint at top.
	
else {
	// Make array for the shipping address form with state field
	if ($setting_state_field == "show") {
		if ($setting_zip_location == "end") {
			// Show zip at the end of address
			$ship_info = array(
				"ship_first_name" => $MOD_BAKERY['TXT_CUST_FIRST_NAME'],
				"ship_last_name" => $MOD_BAKERY['TXT_CUST_LAST_NAME'],
				"ship_street" => $MOD_BAKERY['TXT_CUST_ADDRESS'], 
				"ship_city" => $MOD_BAKERY['TXT_CUST_CITY'],
				"ship_state" => $MOD_BAKERY['TXT_CUST_STATE'],
				"ship_zip" => $MOD_BAKERY['TXT_CUST_ZIP'], 
				"ship_country" => $MOD_BAKERY['TXT_CUST_COUNTRY']
				);
			$length = array(
				"ship_first_name" => "50",
				"ship_last_name" => "50",
				"ship_street" => "50", 
				"ship_zip" => "10", 
				"ship_city" => "50",
				"ship_state" => "50"
				);
		} else {
			// Show zip inside of address
			$ship_info = array(
				"ship_first_name" => $MOD_BAKERY['TXT_CUST_FIRST_NAME'],
				"ship_last_name" => $MOD_BAKERY['TXT_CUST_LAST_NAME'],
				"ship_street" => $MOD_BAKERY['TXT_CUST_ADDRESS'], 
				"ship_zip" => $MOD_BAKERY['TXT_CUST_ZIP'], 
				"ship_city" => $MOD_BAKERY['TXT_CUST_CITY'],
				"ship_state" => $MOD_BAKERY['TXT_CUST_STATE'],
				"ship_country" => $MOD_BAKERY['TXT_CUST_COUNTRY']
				);
			$length = array(
				"ship_first_name" => "50", 
				"ship_last_name" => "50", 
				"ship_street" => "50", 
				"ship_zip" => "10", 
				"ship_city" => "50",
				"ship_state" => "50"
				);
		}
	}
	// Make array for the shipping address form w/o state field
	else {
		if ($setting_zip_location == "end") {
			// Show zip at the end of address
			$ship_info = array(
				"ship_first_name" => $MOD_BAKERY['TXT_CUST_FIRST_NAME'],
				"ship_last_name" => $MOD_BAKERY['TXT_CUST_LAST_NAME'],
				"ship_street" => $MOD_BAKERY['TXT_CUST_ADDRESS'],
				"ship_city" => $MOD_BAKERY['TXT_CUST_CITY'], 
				"ship_zip" => $MOD_BAKERY['TXT_CUST_ZIP'],
				"ship_country" => $MOD_BAKERY['TXT_CUST_COUNTRY']
				);
			$length = array(
				"ship_first_name" => "50",
				"ship_last_name" => "50",
				"ship_street" => "50",
				"ship_zip" => "10", 
				"ship_city" => "50"
				);
		} else {
			// Show zip inside of address
			$ship_info = array(
				"ship_first_name" => $MOD_BAKERY['TXT_CUST_FIRST_NAME'],
				"ship_last_name" => $MOD_BAKERY['TXT_CUST_LAST_NAME'],
				"ship_street" => $MOD_BAKERY['TXT_CUST_ADDRESS'], 
				"ship_zip" => $MOD_BAKERY['TXT_CUST_ZIP'], 
				"ship_city" => $MOD_BAKERY['TXT_CUST_CITY'], 
				"ship_country" => $MOD_BAKERY['TXT_CUST_COUNTRY']
				);
			$length = array(
				"ship_first_name" => "50", 
				"ship_last_name" => "50",
				"ship_street" => "50",
				"ship_zip" => "10",
				"ship_city" => "50"
				);
		}
	}


	// Show ship form title using template file
	$tpl->set_var(array(
		'TXT_FILL_IN_SHIP_ADDRESS'	=>	$MOD_BAKERY['TXT_FILL_IN_SHIP_ADDRESS']
	));
	$tpl->parse('form', 'ship_title_block', true);

	
	// Initialize vars
	$country_options = "";
	$state_options = "";
	
	// Loop through all fields and generate the shipping address form
	foreach ($ship_info as $field => $value) {
	
		// Generate country dropdown menu...
		if ($field == "ship_country") {
			// Prepare ship country options
			for ($n = 1; $n <= count($MOD_BAKERY['TXT_COUNTRY_NAME']); $n++) {
				$country = $MOD_BAKERY['TXT_COUNTRY_NAME'][$n];
				$country_code = $MOD_BAKERY['TXT_COUNTRY_CODE'][$n];
				$selected_country = ($country_code == @$_POST['country'] || $country_code == @$ship_country) ? " selected='selected'" : "";
				$country_options .= "\n\t\t\t<option value='$country_code'$selected_country>$country</option>";
			}
			// Show ship country block using template file
			$tpl->set_var(array(
				'TXT_CUST_COUNTRY'	=>	$MOD_BAKERY['TXT_CUST_COUNTRY'],
				'SELECT_SHOP_COUNTRY'	=>	$select_shop_country,
				'COUNTRY_OPTIONS'	=>	$country_options
			));
			$tpl->parse('form', 'ship_country_block', true);
		}
		
		else {
			// Generate state dropdown menu...
			if ($use_states && $field == "ship_state") {
				// Prepare cust state options
				for ($n = 1; $n <= count($MOD_BAKERY['TXT_STATE_NAME']); $n++) {
					$state = $MOD_BAKERY['TXT_STATE_NAME'][$n];
					$state_code = $MOD_BAKERY['TXT_STATE_CODE'][$n];
					$selected_state = ($state_code == @$_POST['cust_state'] || $state_code == @$ship_state) ? " selected='selected'" : "";
					$state_options .= "\n\t\t\t<option value='$state_code'$selected_state>$state</option>";
				}
				// Show cust state options block using template file
				$tpl->set_var(array(
					'TXT_CUST_STATE'	=>	$MOD_BAKERY['TXT_CUST_STATE'],
					'STATE_OPTIONS'		=>	$state_options
				));
				$tpl->parse('form', 'ship_state_block', true);
			}
	
	
			// Generate all other fields
			// Add css class (red background) if the textfield is blank or incorrect
			$css_error_class = isset($error_bg) && in_array($field, $error_bg) ? 'mod_bakery_errorbg_f ' : '';
			// Show ship textfields block using template file
			$tpl->set_var(array(
				'TR_ID'			=>	$field."_text",
				'LABEL'			=>	$value,
				'CSS_ERROR_CLASS'	=>	$css_error_class,
				'NAME'			=>	$field,
				'VALUE'			=>	htmlspecialchars(@$$field, ENT_QUOTES),
				'MAXLENGHT'		=>	$length[$field]
			));
			$tpl->parse('form', 'ship_textfields_block', true);
		}
	}

	// Show the submit button and a button to hide the shipping address form at the bottom of the form...
	if ($setting_shipping_form == "request" || $setting_shipping_form == "hideable") {
		$tpl->set_var(array(
			'TXT_BACK_TO_CART'	=>	$MOD_BAKERY['TXT_BACK_TO_CART'],
			'TXT_HIDE_SHIP_FORM'	=>	$MOD_BAKERY['TXT_HIDE_SHIP_FORM'],
			'TXT_SUBMIT_ORDER'	=>	$MOD_BAKERY['TXT_SUBMIT_ORDER']
		));
		$tpl->parse('form', 'ship_buttons_block', true);
	}
	// ...or show the submit button
	elseif (isset($_SESSION['bakery']['ship_form'])) {
		$tpl->set_var(array(
			'TXT_BACK_TO_CART'	=>	$MOD_BAKERY['TXT_BACK_TO_CART'],
			'TXT_SUBMIT_ORDER'	=>	$MOD_BAKERY['TXT_SUBMIT_ORDER']
		));
		$tpl->parse('form', 'ship_button_block', true);
	}
}



// PARSE FORM TEMPLATE
// *******************

// Add analytics stuff to main block
$tpl->set_var(array(
	'ANALYTICS_FILENAME'	=>	$bakery_analytics ['filename'],
	'ANALYTICS_PAGETITLE'	=>	$bakery_analytics ['pagetitle']
	));
$tpl->parse('form', 'main_block', true);

$tpl->pparse('output', 'form');

// Initialize js to toggle customer/shipping state text field/select list
if (isset($_SESSION['bakery']['ship_form'])) {
	echo "<script type='text/javascript'>
		<!--
		mod_bakery_toggle_state_f('$select_shop_country', 'cust', 0);
		mod_bakery_toggle_state_f('$select_shop_country', 'ship', 0);
		-->
	</script>
	";
} else {
	echo "<script type='text/javascript'>
		<!--
		mod_bakery_toggle_state_f('$select_shop_country', 'cust', 0);
		-->
	</script>
	";
}

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
	With ob_end_flush() the output filter will be disabled for Bakery address form page
	If you are using e.g. ob_start in the index.php of your template it is possible that you will indicate problems
*/
if ($filter_settings['email_filter'] && !($filter_settings['at_replacement']=='@' && $filter_settings['dot_replacement']=='.')) { 
	ob_end_flush();
}


?>