/*
  Javascript routines for WebsiteBaker module Bakery
  Copyright (C) 2011, Christoph Marti, fberke

  This Javascript routines are free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  The Javascript routines are distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/



// **********************************************************************************
//   Function to delete an item in the cart
// **********************************************************************************

function mod_bakery_delete_item_f(id) {
	if (id != '') {
		document.getElementById('id_' + id).value=0;
		document.getElementById('update_cart').click();
	}
}



// **********************************************************************************
//   Function to toggle between state text field and drop down menu
// **********************************************************************************

function mod_bakery_toggle_state_f(shopCountry, type, clean) {
	if (shopCountry != '') {
		var country = document.getElementsByName(type + '_country')[0].value;
		if (country == shopCountry) {
			document.getElementById(type + '_state_text').style.display = 'none';
			document.getElementById(type + '_state_select').style.display = 'block';
			document.getElementsByName(type + '_state')[1].value = document.getElementsByName(type + '_state')[0].value;
		} else {
			document.getElementById(type + '_state_select').style.display = 'none';
			document.getElementById(type + '_state_text').style.display = 'block';
			if (clean == 1) {
				document.getElementsByName(type + '_state')[1].value = '';
				document.getElementsByName(type + '_state')[1].focus();
			}
		}
	}
}



// **********************************************************************************
//   Functions to take over the state select value to the state text field
// **********************************************************************************

function mod_bakery_synchro_cust_state_f() {
	document.getElementsByName('cust_state')[1].value = document.getElementsByName('cust_state')[0].value;
}

function mod_bakery_synchro_ship_state_f() {
	document.getElementsByName('ship_state')[1].value = document.getElementsByName('ship_state')[0].value;
}



// **********************************************************************************
//   Function to check if customer has agreed to the legal terms of the shop
// **********************************************************************************

function checkLegal(txt_tac, txt_cancellation, txt_privacy) {
	if (
		//((document.getElementById("agree_tac").type != hidden)
		((txt_tac != "") && (document.getElementById("agree_tac").checked != true))
	) {
		alert(txt_tac);
		document.getElementById("agree_tac").focus();
		return false;
	}
	
	else if (
		//((document.getElementById("agree_cancellation").type != hidden)
		((txt_cancellation != "") && (document.getElementById("agree_cancellation").checked != true))
	) {
		alert(txt_cancellation);
		document.getElementById("agree_cancellation").focus();
		return false;
	}
	
	else if (
		//((document.getElementById("agree_privacy").type != hidden)
		((txt_privacy != "") && (document.getElementById("agree_privacy").checked != true))
	) {
		alert(txt_privacy);
		document.getElementById("agree_privacy").focus();
		return false;
	}
	
	else {
		return true;
	}
}
