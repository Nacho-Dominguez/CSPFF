<?php

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
	case 'new':
	case 'newparent':
	case 'edit':
	case 'editA':
		TOOLBAR_location::_EDIT();
		break;
		
	case 'addheard':
	case 'addreason':
	case 'editheard':
	case 'editreason':
	    TOOLBAR_location::_BACKONLY();
	    break;
	    
	default:
		TOOLBAR_location::_DEFAULT();
		break;
}
?>