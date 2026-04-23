<?php
/**
* @version $Id: mod_quickicon.php 1004 2005-11-13 17:18:18Z stingrey $
* @package Joomla
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

if (!defined( '_JOS_QUICKICON_MODULE' )) {
	/** ensure that functions are declared only once */
	define( '_JOS_QUICKICON_MODULE', 1 );

	function quickiconButton( $link, $image, $text ) {
		global $my, $acl;
		?>
		<div style="float:left;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo mosAdminMenus::imageCheckAdmin( $image, '/administrator/images/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}

	?>
	<div id="cpanel">
		<?php
		$isSuperAdmin = ($my->gid == 25);
		$canAdminAll = $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' );
		if ($canAdminAll || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_course' )) {
			$link = 'index2.php?option=com_course';
			quickiconButton( $link, 'generic.png', 'Courses' );
		}

		if ($canAdminAll || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_court' )) {
			$link = 'index2.php?option=com_court';
			quickiconButton( $link, 'frontpage.png', 'Courts' );
		}

		if ($canAdminAll || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_location' )) {
			$link = 'index2.php?option=com_location';
			quickiconButton( $link, 'impressions.png', 'Locations' );
		}

		if ($canAdminAll || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_pay' )) {
			$link = 'index2.php?option=com_pay';
			quickiconButton( $link, 'browser.png', 'Payments' );
		}

		if ($canAdminAll || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_student' )) {
			$link = 'index2.php?option=com_student';
			quickiconButton( $link, 'user.png', 'Students' );
		}

		if ($canAdminAll || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_stats' )) {
			$link = 'index2.php?option=com_stats';
			quickiconButton( $link, 'query.png', 'Statistics' );
		}

		if ($acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' )) {
			$link = 'index2.php?option=com_users';
			quickiconButton( $link, 'user.png', 'Users' );
		}

    if (PlatformConfig::turnOnRequestSupplies) {
      if ($canAdminAll || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_instructor' )) {
        $link = 'index2.php?option=com_instructor&task=supplyform';
        quickiconButton( $link, 'systeminfo.png', 'Request Supplies' );
      }
    }

    if (PlatformConfig::turnOnInstructorTimesheet) {
        if (PlatformConfig::instructorTimesheetForInstructors || A25_DI::User()->isAdminOrHigher()) {
            if ($canAdminAll || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_instructor' )) {
                $link = 'index2.php?option=com_instructor&task=timeform';
                quickiconButton( $link, 'clock.png', 'Instructor Timesheet' );
            }
        }
    }

		if ($isSuperAdmin) {
			$link = 'index2.php?option=com_content&sectionid=0';
			quickiconButton( $link, 'addedit.png', 'Content Items Manager' );

			$link = 'index2.php?option=com_frontpage';
			quickiconButton( $link, 'frontpage.png', 'Frontpage Manager' );

			$link = 'index2.php?option=com_sections&amp;scope=content';
			quickiconButton( $link, 'sections.png', 'Section Manager' );

			$link = 'index2.php?option=com_categories&amp;section=content';
			quickiconButton( $link, 'categories.png', 'Category Manager' );
		}
			
		fireAfterAdminButtons();

		if ($isSuperAdmin) {
			$link = 'index2.php?option=com_media';
			quickiconButton( $link, 'mediamanager.png', 'Media Manager' );
		}

		if ($isSuperAdmin) {
			$link = 'index2.php?option=com_trash';
			quickiconButton( $link, 'trash.png', 'Trash Manager' );
		}

		if ($isSuperAdmin) {
			$link = 'index2.php?option=com_menumanager';
			quickiconButton( $link, 'menu.png', 'Menu Manager' );
		}

		if ($isSuperAdmin) {
			$link = 'index2.php?option=com_languages';
			quickiconButton( $link, 'langmanager.png', 'Language Manager' );
		}

		if ($isSuperAdmin) {
			$link = 'index2.php?option=com_config&hidemainmenu=1';
			quickiconButton( $link, 'config.png', 'Global Configuration' );
		}
    
    if (A25_DI::User()->isAdminOrHigher()) {
      $link = 'documentation/faq';
      quickiconButton($link, 'question.png', 'Help / FAQ');
    }
		?>
	</div>
	<?php
}

function fireAfterAdminButtons()
{
	foreach (A25_ListenerManager::all() as $listener) {
		if ($listener instanceof A25_ListenerI_AddIcons) {
			$listener->afterAdminButtons();
		}
	}
}