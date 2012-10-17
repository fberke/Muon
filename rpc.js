/* rpc.js - Realtime Price Calculation for Bakery                    */
/* With this little JavaScript the price of a shop item is refreshed */
/* evey time an item attribute (if available) is selected.           */
/* Basic idea taken from JavaScript-Coder.com                        */
/* Function 'number_format' taken from phpjs.org                     */

function loopSelected() {
	var selectedArray = new Array();
	var selObj = "";
	var count = 0;
  	
  	for (var i = 0; i < selectIDs.length; i++) { // loop through selects

		selObj = document.getElementById(selectIDs[i]);
		
		for (var x = 0; x < selObj.options.length; x++) { // loop through options
			if (selObj.options[x].selected) {
				selectedArray[count] = selObj.options[x].value;
				count++;
			}
		}
		
	}
	return selectedArray;
}


function calculateTotal() {
	var priceID = 0;
	var attrPriceTotal = 0;
	var selectedArray = loopSelected();
	
	for (var y = 0; y < selectedArray.length; y++) {
		priceID = selectedArray[y];
		attrPriceTotal = attrPriceTotal + attrPrices[priceID];
	}

	var priceTotal = basePrice + attrPriceTotal;
	var htmlObj = document.getElementById('totalPrice');
	
	if (typeof number_format == 'function') {
		htmlObj.innerHTML = number_format(priceTotal.toFixed(2), 2, decimalPoint, thousandsSep);
	} else {
		htmlObj.innerHTML = priceTotal.toFixed(2);
	}
}

