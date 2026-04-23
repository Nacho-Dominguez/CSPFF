<?php
/**
 * $URL$
 *
 * @package AliveAt25
 * @subpackage course
 */

/**
 * Set flag that this is a parent file
 */
define( '_VALID_MOS', 1 );

include_once( '../../globals.php' );
require_once( '../../configuration.php' );
require_once( '../../includes/joomla.php' );

require_once( $mosConfig_absolute_path .'/includes/frontend.php' );
require_once( $mosConfig_absolute_path .'/includes/sef.php' );

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );

switch( $task ) {
	case "searchzip":
	default:
		$zip = trim( mosGetParam( $_REQUEST, 'zip', null ) );
		$radius = trim( mosGetParam( $_REQUEST, 'radius', PlatformConfig::defaultSearchRadius ) );
		return searchZip($zip, $radius);
		break;
}


/**
 * Return the number of upcoming courses within a distance range for a given zip code
 *
 * @param integer $zip
 * @param integer $radius
 * @return array $range
 *
 * @author Christiaan van Woudenberg
 * @version June 28, 2006
 */
function searchZip($zip, $radius = PlatformConfig::defaultSearchRadius) {
	global $database;
	$nearby = false;
	$sql = "SELECT * FROM #__zip_codes WHERE `zip_code`='$zip' AND ABS(`latitude`)>0 AND ABS(`longitude`)>0";
	$database->setQuery($sql);
	$curr = null;
	$database->loadObject($curr);

	if (!isset($curr->latitude)) {
		$sql = "SELECT * FROM #__zip_codes WHERE `zip_code` > " . ((int) $zip-PlatformConfig::zipSearchLimit) .
		  " AND `zip_code` < " . ((int) $zip+PlatformConfig::zipSearchLimit) .
		  " AND ABS(`latitude`) > 0 AND ABS(`longitude`) > 0 ORDER BY ABS(`zip_code`-" . (int) $zip . ") LIMIT 1";
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

	$result = array(
		'lathigh' => (float) ((float) $curr->latitude + $latrange),
		'latlow' => (float) ($curr->latitude - $latrange),
		'longhigh' => (float) ($curr->longitude + $longrange),
		'longlow' => (float) ($curr->longitude - $longrange)
		);

    //do not include school course in results (modified by GCH, 24 Apr 2007)
	$sql = "SELECT COUNT(*) FROM #__course c"
		. " LEFT JOIN #__location l USING (`location_id`)"
		. " LEFT JOIN #__zip_codes z ON (l.`zip` = z.`zip_code`)"
		. " WHERE `latitude` < " . $result['lathigh'] . " AND `latitude` > " . $result['latlow']
		. " AND `longitude` < " . $result['longhigh'] . " AND `longitude` > " . $result['longlow']
		. " AND c.`course_start_date`>NOW()"
		;
	$database->setQuery($sql);
	$num = $database->loadResult();
	//echo $database->_errorMsg;

	$nearstr = '';
	if ($nearby) {
		$nearstr = ' Please note: ' . $zip . ' could not be located in our database, so a nearby zip code was used instead.';
	}

	if ($num == 0) {
		echo '<div class="required">No upcoming courses are within ' . $radius . ' miles of ' . $zip . '.</div>Please increase your search radius.';
	} elseif ($num > PlatformConfig::zipResultUpperLimit) {
		echo $num . ' courses found within ' . $radius . ' miles of ' . $curr->zip_code . '.' . $nearstr . '<div class="required">You may wish to reduce your search radius to see courses closest to you.</div><input type="submit" value="Browse ' . $num . ' Nearest Courses" />';
	} else {
		echo $num . ' courses found within ' . $radius . ' miles of ' . $curr->zip_code . '.' . $nearstr . '<br /><input type="submit" value="Browse These ' . $num . ' Courses" />';
	}
}
