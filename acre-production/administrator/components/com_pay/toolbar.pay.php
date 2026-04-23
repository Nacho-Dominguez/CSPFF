<?php
/**
* Alive At 25 : User toolbar handler
* @version $Id: toolbar.pay.php 41 2006-01-11 23:36:58Z beat $
* @package Alive At 25
* @subpackage toolbar.pay.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
	case 'payform':
		TOOLBAR_pay::_PAYFORM();
		break;

	case 'payformA':
		TOOLBAR_pay::_PAYFORMA();
		break;
		
	default:
		TOOLBAR_pay::_DEFAULT();
		break;
}
?>