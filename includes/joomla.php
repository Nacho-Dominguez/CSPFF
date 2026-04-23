<?php
/**
* @version $Id: joomla.php 4127 2006-06-25 20:00:22Z stingrey $
* @package AliveAt25
* @subpackage Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

require_once(dirname(__FILE__) . '/../autoload.php');
require(dirname(__FILE__) . '/../configuration.php');

require_once( $mosConfig_absolute_path . '/includes/version.php' );
require_once( $mosConfig_absolute_path . '/includes/database.php' );
require_once( $mosConfig_absolute_path . '/includes/gacl.class.php' );
require_once( $mosConfig_absolute_path . '/includes/gacl_api.class.php' );
require_once( $mosConfig_absolute_path . '/includes/phpmailer/class.phpmailer.php' );
require_once( $mosConfig_absolute_path . '/includes/joomla.xml.php' );
require_once( $mosConfig_absolute_path . '/includes/phpInputFilter/class.inputfilter.php' );

require_once(dirname(__FILE__) . '/joomlaClasses.php');


/**
 * define _MOS_MAMBO_INCLUDED
 */
define( '_MOS_MAMBO_INCLUDED', 1 );


if (phpversion() < '4.2.0') {
	require_once( dirname( __FILE__ ) . '/compat.php41x.php' );
}
if (phpversion() < '4.3.0') {
	require_once( dirname( __FILE__ ) . '/compat.php42x.php' );
}
if (version_compare( phpversion(), '5.0' ) < 0) {
	require_once( dirname( __FILE__ ) . '/compat.php50x.php' );
}

@set_magic_quotes_runtime( 0 );

if ( @$mosConfig_error_reporting === 0 || @$mosConfig_error_reporting === '0' ) {
	error_reporting( 0 );
} else if (@$mosConfig_error_reporting > 0) {
	error_reporting( $mosConfig_error_reporting );
}


/**
 * This is the global $database, the database connection used throughout the 
 * site.
 * 
 * @global database $database
 * @name $database
 */
$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );

if ($database->getErrorNum()) {
	$mosSystemError = $database->getErrorNum();
	throw new Exception ("Database error # $mosSystemError");
}
$database->debug( $mosConfig_debug );

/**
 * This is the global $acl used throughout the site.  I think it has something 
 * to do with access rights.
 * @global gacl_api $acl
 * @name $acl
 */
$acl = new gacl_api();

// platform neurtral url handling
if ( isset( $_SERVER['REQUEST_URI'] ) ) {
	$request_uri = $_SERVER['REQUEST_URI'];
} else {
	$request_uri = $_SERVER['SCRIPT_NAME'];
	// Append the query string if it exists and isn't null
	if ( isset( $_SERVER['QUERY_STRING'] ) && !empty( $_SERVER['QUERY_STRING'] ) ) {
		$request_uri .= '?' . $_SERVER['QUERY_STRING'];
	}
}
$_SERVER['REQUEST_URI'] = $request_uri;

// current server time
$now = date( 'Y-m-d H:i', time() );
DEFINE( '_CURRENT_SERVER_TIME', $now );
DEFINE( '_CURRENT_SERVER_TIME_FORMAT', '%Y-%m-%d %H:%M:%S' );

// Non http/https URL Schemes
$url_schemes = 'data:, file:, ftp:, gopher:, imap:, ldap:, mailto:, news:, nntp:, telnet:, javascript:, irc:';
DEFINE( '_URL_SCHEMES', $url_schemes );

// disable strict mode in MySQL 5
if (!defined( '_JOS_SET_SQLMODE' )) {
	/** ensure that functions are declared only once */
	define( '_JOS_SET_SQLMODE', 1 );

	// if running mysql 5, set sql-mode to mysql40 - thereby circumventing strict mode problems
	if ( strpos( $database->getVersion(), '5' ) === 0 ) {
		$query = "SET sql_mode = 'MYSQL40'";
		$database->setQuery( $query );
		$database->query();
	}
}

/** @global mosPlugin $_MAMBOTS */
$_MAMBOTS = new mosMambotHandler();

?>
