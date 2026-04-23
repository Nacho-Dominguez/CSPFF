<?php
/**
* @version $Id: language.php 328 2005-10-02 15:39:51Z Jinx $
* @package Joomla
* @subpackage Installer
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

// ensure user has access to this function
//if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
//                | $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_jce' ))) {
//        mosRedirect( 'index2.php', _NOT_AUTH );
//}

global $mosConfig_absolute_path, $database;
$database->setQuery( "SELECT lang FROM #__jce_langs WHERE published= '1'" );
$lang = $database->loadResult();

require_once( $mosConfig_absolute_path."/administrator/components/com_jce/language/".$lang.".php" );

$backlink = '<a href="index2.php?option=com_jce&task=lang">'._JCE_LANG_BACK.'</a>';
HTML_installer::showInstallForm( _JCE_LANG_HEADING_INSTALL, $option, 'language', '', dirname(__FILE__), $backlink );
?>
<table class="content">
<?php
writableCell( 'administrator/components/com_jce/language' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/themes/advanced/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/advimage/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/directionality/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/emotions/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/fullscreen/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/paste/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/preview/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/print/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/searchreplace/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/table/langs' );
?>
</table>
