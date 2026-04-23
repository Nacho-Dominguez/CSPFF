<?php
/**
* Alive At 25 : User toolbar handler
* @version $Id: toolbar.court.php 41 2006-01-11 23:36:58Z beat $
* @package Alive At 25
* @subpackage toolbar.court.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
	case 'new':
	case 'edit':
	case 'editA':
		TOOLBAR_court::_EDIT();
		break;
		
	case 'othercourt':
	    TOOLBAR_court::_OTHER();
	    break;

	default:
		TOOLBAR_court::_DEFAULT();
		break;
}
?>