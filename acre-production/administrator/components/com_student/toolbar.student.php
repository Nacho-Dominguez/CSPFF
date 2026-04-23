<?php
/**
 * Alive At 25 : User toolbar handler.
 * $URL$
 * 
 * @author Christiaan van Woudenberg
 * @author Thomas Albright
 * @version $LastChangedRevision$, $Date: 2007-12-13$
 * @since Revision 1, 2007-11-22
 * @package AliveAt25
 * @subpackage com_student
 */

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
	case 'studentForm':
		break;

	case 'view':
	case 'viewA':
		TOOLBAR_student::_VIEW();
		break;

	case 'noteform':
		TOOLBAR_student::_NOTE();
		break;

	case 'msgform':
		TOOLBAR_student::_MSG();
		break;

	case 'new':
	case 'edit':
	case 'editA':
		TOOLBAR_student::_EDIT();
		break;

	case 'enrollform':
	case 'enrollview':
	    TOOLBAR_student::_ENROLL();
	    break;
	    
	default:
    if($my->isCourtAdministrator()) {
      TOOLBAR_student::_COURTADMIN();
    } else {
		  TOOLBAR_student::_DEFAULT();
    }
		break;
}
?>
