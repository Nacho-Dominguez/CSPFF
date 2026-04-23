<?php
// Set flag that this is a parent file
define( '_VALID_MOS', 1 );

include_once( '../../globals.php' );
require_once( '../../configuration.php' );
require_once( '../../includes/joomla.php' );

require_once( $mosConfig_absolute_path .'/includes/frontend.php' );
require_once( $mosConfig_absolute_path .'/includes/sef.php' );

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );

switch( $task ) {
	case "search":
	default:
		$search = trim( mosGetParam( $_REQUEST, 'search', null ) );
		$hs = (int) mosGetParam( $_REQUEST, 'hs', 0 );
		$zip = trim( mosGetParam( $_REQUEST, 'zip', null ) );
		$radius = trim( mosGetParam( $_REQUEST, 'radius', PlatformConfig::defaultSearchRadius ) );
		search($search, $hs, $zip, $radius);
		break;
}


/**
 * Return the number of upcoming courses within a distance range for a given zip code
 *
 * @param string $search
 * @param integer $zip
 * @maran integer $radius
 * @return string
 *
 * @author Christiaan van Woudenberg
 * @version July 7, 2006
 */
function search($search, $hs=0, $zip=null, $radius = PlatformConfig::defaultSearchRadius) {
	global $database;
	if ($zip) {
		$nearby = false;
		$sql = "SELECT * FROM #__zip_codes WHERE `zip_code`='$zip' AND ABS(`latitude`)>0 AND ABS(`longitude`)>0";
		$database->setQuery($sql);
		$curr = null;
		$database->loadObject($curr);

		if (!isset($curr->latitude)) {
			$sql = "SELECT * FROM #__zip_codes WHERE `zip_code` > " . ((int) $zip-PlatformConfig::zipSearchLimit) . " AND `zip_code` < " . ((int) $zip+PlatformConfig::zipSearchLimit) . " AND ABS(`latitude`)>0 AND ABS(`longitude`)>0 ORDER BY ABS(`zip_code`-" . (int) $zip . ") LIMIT 1";
			$database->setQuery($sql);
			$curr = null;
			$database->loadObject($curr);

			// Could not find a zip within the current search criterion.
			if (!isset($curr->latitude)) {
				echo '<div class="required">Could not locate the current zip code in our database.</div>';
				echo 'Please try another zip code, or <a href="' . PlatformConfig::findACourseUrl() . '">browse all courses</a> instead.';
				exit();
			} else {
				$nearby = true;
			}
		}

		$latrange = $radius / ((6076 / 5280) * 60);
		$longrange = $radius / (((cos($curr->latitude * 3.141592653589 / 180) * 6076) / 5280) * 60);

		$range = array(
			'lathigh' => (float) ((float) $curr->latitude + $latrange),
			'latlow' => (float) ($curr->latitude - $latrange),
			'longhigh' => (float) ($curr->longitude + $longrange),
			'longlow' => (float) ($curr->longitude - $longrange)
			);
	}

	$sql = "SELECT l.`location_id`,CONCAT(l.`location_name`, '<span class=\"informal\"> - ', l.`city`, ', ', l.`state`, ' (#NUMCOURSES#)</span>') AS `location_name`, COUNT(c.`location_id`) AS `num_courses`"
		. "\n FROM #__location l"
		.	"\n LEFT JOIN #__course c ON (l.`location_id`=c.`location_id` AND c.`course_start_date`>NOW())"
		;
	if (isset($range)) {
		$sql .= "\n LEFT JOIN #__zip_codes z ON (l.`zip` = z.`zip_code`)"
			. "\n WHERE l.`is_location` AND l.`published`"
			. "\n AND latitude` < " . $range['lathigh'] . " AND `latitude` > " . $range['latlow']
			. "\n AND `longitude` < " . $range['longhigh'] . " AND `longitude` > " . $range['longlow']
			. "\n AND l.`location_name` LIKE '%$search%'"
			;
	} else {
		$sql .= "\n WHERE l.`is_location` AND l.`published` AND l.`location_name` LIKE '%$search%'"
			;
	}
	if ($hs) {
		$sql .= "\n AND `is_highschool`";
	}
	$sql .= " AND (c.`course_start_date`>NOW() OR c.`course_start_date` IS NULL)"
			. "\n GROUP BY `location_id`"
			. "\n ORDER BY `location_name`"
			. "\n LIMIT 15"
			;

	$database->setQuery($sql);
	$rows = $database->loadObjectList();
	//echo str_replace('#_','jos',$sql);
	//print_r($rows);
	//echo $database->_errorMsg;

	if (!count($rows)) {
		echo '<ul><li><i>No matching ';
		echo $hs ? strtolower(PlatformConfig::schoolType . 's') : 'locations';
		echo ' found. Try browsing by zip code instead.</i></li></ul>';
		return;
	}
	echo '<ul>';
	$pref = ($hs) ? 'hid' : 'lid';
	foreach ($rows as $row) {
		$numcourses = (int) $row->num_courses;
		if ($numcourses == 0) {
			$cstr = 'No upcoming courses';
		} elseif ($numcourses == 1) {
			$cstr = '1 upcoming course';
		} else {
			$cstr = $numcourses . ' upcoming courses';
		}
		echo '<li onClick="$(\'' . $pref .'\').value=' . $row->location_id . '; $(\'' . $pref . 'button\').style.display=\'block\';">' . str_replace('#NUMCOURSES#',$cstr,$row->location_name) . '</li>';
	}
	echo '</ul>';
}
?>
