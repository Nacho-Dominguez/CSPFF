// <?php !! This fools phpdocumentor into parsing this file
/**
* Alive At 25
* @version $Id: admin.location.html.php 264 2006-04-28 15:41:14Z beat $
* @package Alive At 25
* @subpackage admin.location.html.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/**
* Fixes up phone numbers to xxx-xxx-xxxx format
* @param string The element which contains the phone number
* @author Christiaan van Woudenberg
* @date June 27, 2006
*/
function fixPhone(elem) {

	var re= /\D/;
	// test for this format: xxx-xxx-xxxx
	var re2 = /^\d{3}-\d{3}-\d{4}/;

	var num = elem.value;

	var newNum;
	if (num != "" && re2.test(num)!=true) {
		if (num != "") {
			while (re.test(num)) {
				num = num.replace(re,"");
			}
		}

		if (num.length > 0 && num.length != 10){
			alert('Please enter a 10 digit phone number using XXX-XXX-XXXX format.');
			elem.select();
		} else {
			// for format xxx-xxx-xxxx
			newNum = num.substring(0,3) + '-' + num.substring(3,6) + '-' + num.substring(6,10);
			elem.value=newNum;
		}
	}
}

// From http://www.mredkj.com/tutorials/tutorial_mixed2b.html
function addOption(theSel, theText, theValue) {
  var newOpt = new Option(theText, theValue);
  var selLength = theSel.length;
  theSel.options[selLength] = newOpt;
}

function deleteOption(theSel, theIndex) {
  var selLength = theSel.length;
  if(selLength>0) {
    theSel.options[theIndex] = null;
  }
}

function moveOptions(theSelFrom, theSelTo) {
  var selLength = theSelFrom.length;
  var selectedText = new Array();
  var selectedValues = new Array();
  var selectedCount = 0;

  var i;

  // Find the selected Options in reverse order
  // and delete them from the 'from' Select.
  for(i=selLength-1; i>=0; i--) {
    if(theSelFrom.options[i].selected) {
      selectedText[selectedCount] = theSelFrom.options[i].text;
      selectedValues[selectedCount] = theSelFrom.options[i].value;
      deleteOption(theSelFrom, i);
      selectedCount++;
    }
  }

  // Add the selected text/values in reverse order.
  // This will add the Options to the 'to' Select
  // in the same order as they were in the 'from' Select.
  for(i=selectedCount-1; i>=0; i--) {
    addOption(theSelTo, selectedText[i], selectedValues[i]);
  }
}

function turnon( elemName ) {
	elem = $(elemName);
	if (elem) {
		for (n=0;n<elem.length;n++) {
			elem.options[n].selected = true;
		}
	}
}

function markAll( classname, toselect ) {
	var form = document.adminForm;
	var selems = form.getElementsByTagName("select");

	for (var h = 0; h < selems.length; h++) {
		if ( selems[h].className == 'markall'+classname) {
			selems[h].selectedIndex = toselect;
		}
	}
}

var stripe = function() {
	var tables = document.getElementsByTagName("table");

	for(var x=0;x!=tables.length;x++){
		var table = tables[x];
		if (! table) { return; }
		if ( table.className == 'striped') {

			var tbodies = table.getElementsByTagName("tbody");

			for (var h = 0; h < tbodies.length; h++) {
				var even = true;
				var trs = tbodies[h].getElementsByTagName("tr");

				for (var i = 0; i < trs.length; i++) {
					trs[i].onmouseover=function(){
						this.className += " ruled"; return false
					}
					trs[i].onmouseout=function(){
						this.className = this.className.replace("ruled", ""); return false
					}

					if(even)
						trs[i].className += " even";

					even = !even;
				}
			}
		}
	}
}

window.onload = stripe;