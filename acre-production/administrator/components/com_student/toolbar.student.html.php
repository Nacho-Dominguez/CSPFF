<?php
/**
 * Student Admin Button Bar HTML
 * $URL$
 * 
 * @package aliveat25_components
 * @subpackage student
 * @author Christiaan van Woudenberg
 * @author Thomas Albright
 * @version $LastChangedRevision$, $Date$
 * @since Revision 1, 2007-11-22
 */

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Contains the functions to print the buttons in the Joomla administrator for 
 * com_student.
 * 
 * @package aliveat25_components
 * @subpackage student
 * @author Christiaan van Woudenberg
 * @since Revision 1, 2007-11-22
 * @static
 */
class TOOLBAR_student {
	/**
	* Draws the menu for a New Student
	*/
	function _EDIT() {
//		global $id;
//
//		mosMenuBar::startTable();
//		mosMenuBar::save();
//		mosMenuBar::spacer();
//		mosMenuBar::apply();
//		mosMenuBar::spacer();
//		if ( $id ) {
//			// for existing content items the button is renamed `close`
//			mosMenuBar::cancel( 'cancel', 'Close' );
//		} else {
//			mosMenuBar::cancel();
//		}
//		mosMenuBar::endTable();
	}

	function _VIEW() {
		mosMenuBar::startTable();
		echo A25_Buttons::toolbarWithUnassumingUrl('Return to List',
				$_SESSION['last_search'], 'restore_f2.png');
		mosMenuBar::endTable();
	}

	function _NOTE() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'savenote', 'Save Note');
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'cancel', 'Cancel' );
		mosMenuBar::endTable();
	}

	function _MSG() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'sendmsg', 'Send');
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'cancel', 'Cancel' );
		mosMenuBar::endTable();
	}
	
	function _ENROLL() {
	    mosMenuBar::startTable();
	    mosMenuBar::back();
	    mosMenuBar::endTable();
	}

  function _COURTADMIN() {
	    mosMenuBar::startTable();
	    mosMenuBar::spacer();
	    mosMenuBar::endTable();
  }

	function _DEFAULT() {
		echo A25_Buttons::toolbar('New', 'option=com_student&task=new', 'groups_f2.png');
	}
}
?>
