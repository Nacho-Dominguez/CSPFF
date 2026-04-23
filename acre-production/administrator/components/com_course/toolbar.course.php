<?php

/**
 * class TOOLBAR_course
 */
require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
	case 'view':
	case 'viewA':
		TOOLBAR_course::_VIEW();
		break;

	case 'newmsg':
		TOOLBAR_course::_MESSAGE();
		break;

	case 'cancelform':
		TOOLBAR_course::_CANCEL();
		break;

	default:
		TOOLBAR_course::_DEFAULT();
		break;
}