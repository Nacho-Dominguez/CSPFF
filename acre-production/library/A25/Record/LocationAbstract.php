<?php

abstract class A25_Record_LocationAbstract extends JosLocation
		implements A25_Interface_HaveSettings, A25_ISelectable
{
	public function construct()
	{
		if (!$this->exists())
			$this->is_location = 1;
	}

	/**
	 * @param integer $id
	 * @return A25_Record_Location
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_Location')->find($id);
    }

	function check()
	{
    // If enrollment deadline has no units, add "hours"
    if (preg_match('/^\d+$/', $this->enrollment_deadline)) {
      $this->enrollment_deadline .= ' hours';
    }

		// check for valid location name
		if (trim($this->location_name == '')) {
			$this->_error = "Location name cannot be empty.";
			return false;
		}

		if (!$this instanceof A25_Record_LocationParent) {
			// check for valid state name
			if (trim($this->state == '')) {
				$this->_error = "Location state cannot be empty.";
				return false;
			}
		}
		if (empty($this->zip)) {
			$this->_error = "Zip code cannot be empty.";
		}
		return true;
	}

	public function getSelectionName()
	{
		return $this->location_name;
	}

	/**
	 * Returns a Google Map link
	 * @author Christiaan van Woudenberg
	 * @version July 7, 2006
	 *
	 * @return string
	 */
	public function googleMap()
	{
		$map = $this->googleMapUrl();
		if ($map) {
			return '<img src="' . A25_Link::to('images/pointer_icon_small.png') . '"
style="vertical-align:middle" alt="pointer"/>
<a href="' . $map . '" target="_blank" onClick="alert(\'Google Map results are not guaranteed to be accurate. You are solely responsible for arriving at the course location on time.\')">Google Maps</a>';
		} else {
			return '';
		}
	}

	public function googleMapUrl()
	{
		if (!strlen(trim($this->address_1))) {
			return;
		}
    $link = 'http://maps.google.com/maps?q=' . urlencode($this->address_1);
    if ($this->address_2 != '') {
      $link .= urlencode(' ' . $this->address_2);
    }
    $link .= urlencode(', ' . $this->city . ', ' . $this->state . ' ' . $this->zip);
		return $link;
	}



	/**
	 * Returns the security context of the current location administrator
	 * @author Christiaan van Woudenberg
	 * @version July 7, 2006
	 *
	 * @param integer $user_id
	 * @param integer $location_id
	 * @return string
	 */
	function getLocs( $location_ids = array(), $level=0) {
		global $my, $acl;
		/*
		echo '<p>Doing level ' . $level . '<br  />';
		print_r($location_ids);
		echo '</p>';
		*/

		$parents = array();
		$children = array();

		switch (strtolower($my->usertype)) {
			case "super administrator":
			case "administrator":

					/**
					 * ACL action rule all means the usertype may administer all locations.
					 */
					if ($acl->acl_check( 'action', 'all', 'users', $my->usertype, 'location', 'all' )) {
						return array('all');
					} else {
						return array();
					}

				break;

			case "location administrator":
				$gid = 27;
				break;
			case "instructor":
				$gid = 26;
				break;
			case "court administrator":
				$gid = 23;
        // HACK to allow courts to look at students.
        return array('all');
				break;
		}

		/* Initial pass */
		if ((boolean) $level == 0) {
			/* Fetch parent locations for recursion */
			$sql = "SELECT x.location_id"
				. "\n FROM #__location_user_xref x"
				. "\n LEFT JOIN #__location l USING (`location_id`)"
				. "\n WHERE NOT l.`is_location`"
				. "\n AND x.`gid`=" . $gid
				. "\n AND `user_id` = " . (int) $my->id
				;
			A25_DI::DB()->setQuery($sql);
			$parents = A25_DI::DB()->loadResultList('location_id');
			echo A25_DI::DB()->_errorMsg;

			$hasParents = (count($parents)) ? true : false;

			/* No parents, no new locations! Set ACL restriction accordingly */
			if ($hasParents) {
				$acl->_mos_add_acl( 'action', 'new', 'users', $my->usertype, 'location', 'all' );
				$acl->_mos_add_acl( 'action', 'newparent', 'users', $my->usertype, 'location', 'all' );
			}

			/* Fetch children for the initial context */
			$sql = "SELECT x.location_id"
				. "\n FROM #__location_user_xref x"
				. "\n LEFT JOIN #__location l USING (`location_id`)"
				. "\n WHERE l.`is_location`"
				. "\n AND x.`gid`=" . $gid
				. "\n AND `user_id` = " . (int) $my->id
				;
			A25_DI::DB()->setQuery($sql);
			$children = A25_DI::DB()->loadResultList('location_id');
			echo A25_DI::DB()->_errorMsg;
		}

		/* Fetch child locations for the current location, recurse where appropriate */
		elseif ($level && count($location_ids)) {
			/* Fetch locations with parents for a given location ID */
			$sql = "SELECT l.location_id"
				. "\n FROM #__location l"
				. "\n WHERE NOT l.is_location"
				. "\n AND l.parent IN (" . implode(',',$location_ids) . ")"
				;
			A25_DI::DB()->setQuery($sql);
			$parents = A25_DI::DB()->loadResultList('location_id');
			echo A25_DI::DB()->_errorMsg;

			$hasParents = (count($parents)) ? true : false;

			/* Fetch children with parents for a given location ID */
			$sql = "SELECT l.location_id"
				. "\n FROM #__location l"
				. "\n WHERE l.is_location"
				. "\n AND l.parent IN (" . implode(',',$location_ids) . ")"
				;
			A25_DI::DB()->setQuery($sql);
			$children = A25_DI::DB()->loadResultList('location_id');
			echo A25_DI::DB()->_errorMsg;
		}

		/* Have parents, need to recurse */
		if ($hasParents) {
			$children = $children + $parents + A25_Record_Location::getLocs($parents, $level+1);
		}

		return $children;
	}

	public function getUsers()
	{
		$users = array();

		foreach ($this->LocationUsers as $lu) {
			$users[] = $lu->User;
		}

		return $users;
	}


	/**
	 * Get list of credit types that are available for use (they are active and not full) in an objectlist.
	 * @author Garey Hoffman
	 * @version December 10, 2006
	 *
	 * @return boolean
	 */
	function getLocationParents() {

    	$where = array();
        /* Only show location parents */
    	$where[] = "l.`is_location`=0";

	    $locs  = array();
	    $locs = A25_Record_Location::getLocs();
    	if ( @$locs[0] != 'all' ) {
    		$where[] = "l.location_id IN (" . implode(',',$locs) . ")";
    	}

    	// get the total number of records
    	$query = "SELECT * "
    	. "\n FROM #__location l"
    	. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
    	;
    	A25_DI::DB()->setQuery( $query );

		return A25_DI::DB()->loadObjectList();
	}

	function showLocationInfo( $mode = 'full' ) {
		$str = '';

		if ($mode == 'full') {
			$str = '<table width="100%" border="0">'
				. '<tr>'
				. '<td class="formlabeltop">Location:</td>'
				. '<td><strong>' . $this->location_name . '</strong><br />'
				. $this->address_1 . '<br />'
				;
			$str .= $this->address_2 ? $this->address_2 . '<br />' : '';
			$str .= $this->city . ', ' . $this->state . ' ' . $this->zip
 			. '<br />' . $this->googleMap()
 				. '</td>'
				. '</tr>';
			if ($this->phone) {
				$str .= '<tr><td class="formlabel">Phone Number:</td><td>' . $this->phone . '</td></tr>';
			}
			if ($this->contact) {
				$str .= '<tr><td class="formlabel">Contact:</td><td>' . $this->contact . '</td></tr>';
			}
      $locationComments = A25_DI::PlatformConfig()->locationComments();
      if ($locationComments)
        $str .= '<tr><td colspan="2" class="locationComments">'
          . $locationComments . '</td></tr>';
      $str .= '<tr>'
				. '<td colspan="2">' . $this->description
				. '</tr>'
				. '</table>'
				;
		} elseif ($mode == 'simple') {
				$str =  '<strong>' . $this->location_name . '</strong><br />'
					. $this->address_1 . '<br />'
					;
				$str .= $this->address_2 ? $this->address_2 . '<br />' : '';
				$str .= $this->city . ', ' . $this->zip
					. '<p>' . $this->googleMap() . '</p>'
					;
		}
		return $str;
	}

	public function assignParent(A25_Record_LocationAbstract $locationParentRecord)
	{
        $this->Parent = $locationParentRecord;
    }

	public function getEnrollmentEmailBody()
	{
		$body = $this->getSetting('enrollment_email_body');
		$body = A25_StringReplace::secureUrl($body);
		return $body;
    }
	/**
	 * Returns an array of all location ID's in the hierarchy of this location,
	 * including its own ID.
	 *
	 * It might be quicker to do one db call and get all locations than
	 * do parent id processing with php.
	 */
	public function parentLocationIds()
	{

		$ids = array(0,$this->location_id);
		$location = $this;
		while ($location->parent > 0 && $location->parent != $location->location_id) {
			$location = A25_Record_Location::retrieve($location->parent);
			$ids[] = $location->location_id;
		}

		return $ids;

	}

	public function hearAboutList($isAdmin)
	{
		$parent_locations = $this->parentLocationIds();

		$heard_types = array();
		$heard_types[] = mosHTML::makeOption('','- Select One -');
        if (A25_DI::PlatformConfig()->forbidFrontEndCourtEnrollments && $isAdmin == false) {
		$sql = "SELECT `hear_about_id` AS value, `hear_about_name` AS text"
		   . "\n FROM #__hear_about_type"
		   . "\n WHERE `location_id` IN (" . implode(',',$parent_locations) . ")"
                . "\n AND hear_about_id <> 1"
		   . "\n ORDER BY `location_id`, `priority_id`";
        }
        else {
		$sql = "SELECT `hear_about_id` AS value, `hear_about_name` AS text"
		   . "\n FROM #__hear_about_type"
		   . "\n WHERE `location_id` IN (" . implode(',',$parent_locations) . ")"
		   . "\n ORDER BY `location_id`, `priority_id`";
        }
		A25_DI::DB()->setQuery($sql);
		$heard_types = array_merge($heard_types,A25_DI::DB()->loadObjectList());
		return mosHTML::selectList( $heard_types, 'hear_about_id',
				'class="inputbox" style="max-width: 100%;"', 'value', 'text', null);
	}

	public function getSetting($fieldName)
	{
		$detector = new A25_SettingsDetector();
		return $detector->findLeafSetting($this, $fieldName);
	}
}
