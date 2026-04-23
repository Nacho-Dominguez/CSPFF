<?php

require_once(dirname(__FILE__) . '/../../../../includes/joomla.php');

class A25_OldCom_Admin_EditCourt
{
    function run($my, $locs, $court_id='0', $option='com_court' )
	{


		if ($court_id) {
			// do stuff for existing records
			$row = A25_Record_Court::retrieve( $court_id );
			$row->checkout($my->id);
		} else {
			// do stuff for new records
			$row = new A25_Record_Court();
			$row->published = PlatformConfig::defaultPublished;
		}

		$lists = array();

		// build list of states
		$lists['state'] = A25_SelectListGenerator::generateStateSelectList('state', 'class="inputbox" size="1"', $row->state);


		// Location permissions may only be changed by location parent administrators.
		if (!$row->court_id || array_key_exists($row->parent,$locs) || $locs[0] == 'all') {
			$where = array();
			$where[] = "NOT(`is_location`)";

			if ( @!$locs[0] == 'all') {
				$where[] = "`location_id` IN (" . implode(',',$locs) . ") ";
			}

			$sql = "SELECT `location_id` AS `id`, `parent`, `location_name` AS `name`"
				. "\n FROM #__location l"
				. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
				. "\n ORDER BY `name`"
				;
			A25_DI::DB()->setQuery($sql);
			$src_list = A25_DI::DB()->loadObjectList();
			//echo $database->_errorMsg;

			$sel = array();
			$sel[] = mosHTML::makeOption($row->parent);

			$lists['parent'] = mosHTML::treeSelectList($src_list, 0, array(), 'parent', ' class="inputbox" size="1"', 'value', 'text', $sel );
			if ($row->parent) {
				$lists['parent'] .= mosToolTip('Changing the location parent will change which location administrators have permissions to manage this court.', 'Warning!',null,'warning.png');
			}

			$where = array();
			$where[] = "x.`xref_id` IS NULL";

			if ( @!$locs[0] == 'all') {
				$where[] = "lx.`location_id` IN (" . implode(',',$locs) . ") ";
			}

			// Build list of available court administrators
			$sql = "SELECT DISTINCT u.`id` AS value, u.`name` AS text"
				. "\n FROM #__users u"
				. "\n LEFT JOIN #__location_user_xref lx ON (u.`id` = lx.`user_id`)"
				. "\n LEFT JOIN #__court_user_xref x ON (u.`id` = x.`user_id` AND x.`court_id`=" . (int) $row->court_id . ")"
				. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
				. "\n ORDER BY u.`name`"
				;
			A25_DI::DB()->setQuery($sql);
			$availAdmins = A25_DI::DB()->loadObjectList();
			echo A25_DI::DB()->_errorMsg;

			$lists['availAdmins'] = mosHTML::selectList( $availAdmins, 'availAdmins[]', 'id = "availAdmins" class="inputbox" size="8" multiple="multiple"', 'value', 'text');

			// Build list of current court administrators
			$sql = "SELECT u.`id` AS value, CONCAT(u.`name`,IF(u.`block`,\" (Inactive)\",\"\"))  AS text"
				. "\n FROM #__court_user_xref x"
				. "\n LEFT JOIN #__users u ON (x.`user_id` = u.`id`)"
				. "\n WHERE x.`court_id`=" . (int) $row->court_id
				. "\n ORDER BY u.`name`"
				;
			A25_DI::DB()->setQuery($sql);
			$currAdmins = A25_DI::DB()->loadObjectList();
			echo A25_DI::DB()->_errorMsg;

			$lists['currAdmins'] = mosHTML::selectList( $currAdmins, 'currAdmins[]', 'id = "currAdmins" class="inputbox" size="8" multiple="multiple"', 'value', 'text');
			$currKeys = A25_DI::DB()->loadResultList('value');
			$lists['currAdmins'] .= '<input type="hidden" name="oldAdmins" value="' . implode(',',array_keys($currKeys)) . '" />';
		} else {
			$sql = "SELECT `location_name` FROM #__location l WHERE `location_id`='" . $row->parent . "'";
			A25_DI::DB()->setQuery($sql);
			$lists['parent'] = '<i>' . A25_DI::DB()->loadResult() . '</i>';
		}

		$lists['published'] = mosHTML::yesnoradioList( 'published', '', $row->published );

		A25_OldCom_Admin_EditCourtHtml::editCourt( $row, $lists, $option, $locs );
	}

}
?>
