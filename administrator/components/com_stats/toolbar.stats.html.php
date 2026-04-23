<?php
/**
* Stats Admin Button Bar HTML
* @version $Id$
* @package Alive At 25
* @subpackage toolbar.stats.html.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package Alive At 25
* @subpackage Stats
*/
class TOOLBAR_stats {
	/**
	* Draws the menu for a New Stats
	*/
	function _VIEW() {
		mosMenuBar::startTable();
		mosMenuBar::custom('cpanel','restore.png','restore_f2.png','Return',false);
		mosMenuBar::endTable();
	}

	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}
?>