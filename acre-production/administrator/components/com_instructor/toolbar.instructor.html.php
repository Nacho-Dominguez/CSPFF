<?php
/**
* Instructor Admin Button Bar HTML
* @version $Id$
* @package Alive At 25
* @subpackage toolbar.instructor.html.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package Alive At 25
* @subpackage Instructor
*/
class TOOLBAR_instructor {
	/**
	* Draws the menu for supply request form
	*/
	function _SUPPLYFORM() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('savesupply','Submit Request');
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancelsupply','Cancel');
		mosMenuBar::endTable();
	}

	/**
	* Draws the menu for supply request form
	*/
	function _TIMEFORM() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('savetime','Submit Timesheet');
		mosMenuBar::spacer();
		mosMenuBar::cancel('canceltime','Cancel');
		mosMenuBar::endTable();
	}
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::endTable();
	}
}
?>