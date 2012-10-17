/*
  Javascript routines for WebsiteBaker module Bakery
  Copyright (C) 2011, Christoph Marti

  This Javascript routines are free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  The Javascript routines are distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/



// **********************************************************************************
//   Function to add and remove file type inputs
//   (http://codingforums.com/showthread.php?t=65390)
// **********************************************************************************

function addFile(delTxt) {
	var root = document.getElementById('upload').getElementsByTagName('tr')[0].parentNode;
	var oR   = cE('tr');
	var oC   = cE('td');
	var oI   = cE('input');
	var oS   = cE('span');
	cA(oI,'type','file');
	cA(oI,'name','image[]');
	oS.style.cursor = 'pointer';

	oS.onclick = function() {
		this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
	}

	oS.appendChild(document.createTextNode(delTxt));
	oC.appendChild(oI);
	oC.appendChild(oS);
	oR.appendChild(oC);
	root.appendChild(oR);
}

function cE(el){
	this.obj = document.createElement(el);
	return this.obj;
}

function cA(obj,att,val) {
	obj.setAttribute(att,val);
	return;
}
