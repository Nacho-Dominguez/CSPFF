<?php
require_once(dirname(__FILE__) . '/../../../../includes/sef.php');

class A25_OldCom_Student_CourseBrowseHtml
{
	public function distance($zip1,$zip2) {
		if (!(((int) $zip1)>0 && ((int) $zip2)>0)) { return '-'; }
		if ((int) $zip1 == (int) $zip2) { return '&lt;1 mile'; }

		$sql = "SELECT `zip_code`, `latitude`/(180/PI()) AS `latitude`, `longitude`/(180/PI()) AS `longitude`"
			. "\n FROM #__zip_codes"
			. "\n WHERE `zip_code` IN ($zip1,$zip2)";
		A25_DI::DB()->setQuery($sql);
		$zips = A25_DI::DB()->loadObjectList();

		if (!(count($zips) == 2)) {
			return 'n/a';
		}

		$lat1 = $zips[0]->latitude;
		$lon1 = $zips[0]->longitude;
		$lat2 = $zips[1]->latitude;
		$lon2 = $zips[1]->longitude;

		return round( 3963.1 * acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon2 - $lon1)) ) . ' miles';
		/*

		Where:

			$a1 = lat1 in radians
			$b1 = lon1 in radians
			$a2 = lat2 in radians
			$b2 = lon2 in radians
			$r = radius of the earth in whatever units you want

			The values I use for radius of the earth are:

			3963.1 statute miles
			3443.9 nautical miles
			6378 km
		*/
	}
}
