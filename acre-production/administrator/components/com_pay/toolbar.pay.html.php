<?php
/**
* Pay Admin Button Bar HTML
* @version $Id$
* @package Alive At 25
* @subpackage toolbar.pay.html.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package Alive At 25
* @subpackage Pay
*/
class TOOLBAR_pay {
	/**
	* Draws the menu for Payments
	*/
	function _VIEW() {
		mosMenuBar::startTable();
		mosMenuBar::custom('cpanel','restore.png','restore_f2.png','Return',false);
		mosMenuBar::endTable();
	}

	function _PAYFORM() {
		mosMenuBar::startTable();
		mosMenuBar::custom('cpanel','restore.png','restore_f2.png','Return',false);
		mosMenuBar::endTable();
	}

	function _PAYFORMA() {
		mosMenuBar::startTable();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}
	
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}
}
?>