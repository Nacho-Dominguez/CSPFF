<?php
/**
 * $URL$
 *
 * @package AliveAt25
 * @subpackage course
 * @author Garey Hoffman
 * @version $LastChangedRevision$, $Date$
 */

/**
 * Set flag that this is a parent file
 */
define( '_VALID_MOS', 1 );

//stream_wrapper_register("xlsfile", "xlsStream") or die("Failed to register protocol: xlsfile");

include_once( '../../../globals.php' );
require_once( '../../../configuration.php' );
require_once( $mosConfig_absolute_path . '/includes/joomla.php' );

// must start the session before we create the mainframe object
session_name( md5( $mosConfig_live_site ) );
session_start();

$mainframe = new mosMainFrame( $database, null, '../../..', true );
$mainframe->initSession();

/* Init $my session variable with automatic lockout/redirection */
$my = $mainframe->initSessionAdmin( '', '' );

if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' ) || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_course' ))) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to manage courses.' );
}

require_once( $mosConfig_absolute_path . '/administrator/components/com_course/admin.course.html.php' );

// Get list of administerable locations for the current user.
$locs = A25_Record_Location::getLocs();

if (empty($locs)) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to print this page.' );
}

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );

switch ($task) {
	case 'viewRoster':
	default:
		viewRoster( $id, $option );
		break;
}


/**
 * View roster for printing an individual course
 * @author Garey Hoffman
 * @version October 20, 2006
 *
 * @param integer $course_id
 * @param  string $option
 * @return void
 */
function viewRoster( $course_id, $option='com_course' ) {
	global $my, $locs;

	$course = A25_Record_Course::retrieve( $course_id );

	$lists = array();

	printHeader();

	A25_OldCom_Admin_ViewRosterHtml::viewRoster( $course, $lists, $option );

	printFooter();
}

/**
 * Prints header
 * @author Garey Hoffman
 * @version October 20, 2006
 *
 * @param integer $course_id
 * @param  string $option
 * @return void
 */
function printHeader() {?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $mosConfig_sitename; ?> - Administration [Joomla]</title>
<link rel="stylesheet" href="../../templates/aliveat25_admin/css/template_print_css.css" type="text/css" />
<link rel="stylesheet" href="../../templates/aliveat25_admin/css/theme.css" type="text/css" />
<body bgcolor="White" alink="Black" link="Black" vlink="Black" text="Black">
<div id="wrapper">
	<div id="header">
			<div id="logo"><?php echo PlatformConfig::courseTitleHtml() ?></div>
	</div>
</div>
<div align="center" class="centermain">
	<div class="main">
<? } ?>
<?
/**
 * Prints footer
 * @author Garey Hoffman
 * @version October 20, 2006
 *
 * @param integer $course_id
 * @param  string $option
 * @return void
 */
function printFooter() { ?>
    </div>
</div>

<div align="center" class="footer">
</div>
</body>
</html>
<? } ?>
