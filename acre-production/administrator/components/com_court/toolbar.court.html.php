<?php
/**
* Court Admin Button Bar HTML
* @version $Id$
* @package Alive At 25
* @subpackage toolbar.court.html.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package Alive At 25
* @subpackage Court
*/
class TOOLBAR_court {
	/**
	* Draws the menu for a New Court
	*/
	function _EDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::apply();
		mosMenuBar::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			mosMenuBar::cancel( 'cancel', 'Close' );
		} else {
			mosMenuBar::cancel();
		}
		mosMenuBar::endTable();
	}
	
	function _OTHER() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::publishList('publish','Activate');
		mosMenuBar::spacer();
		mosMenuBar::unpublishList('unpublish','Deactivate');
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::addNewX();
		mosMenuBar::endTable();
	}
}
?>