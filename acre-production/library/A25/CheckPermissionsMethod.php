<?php

/**
 * This class method was originally extracted from admin.course.php.  I believe
 * it also exists in some other admin.___.php files.
 */
class A25_CheckPermissionsMethod
{
	public static function run($recordType, $location_id, $task, $locs, $acl, $my)
	{
		$existingactions = array('edit'=>true, 'save'=>true, 'publish'=>true, 'unpublish'=>true);
		$newactions = array('new'=>true,'newparent'=>true);

		/* Check for usertypes that may perform any action on any course */
		if ($acl->acl_check( 'action', 'all', 'users', $my->usertype, $recordType, 'all' )) {
			return true;
		}

		/* Check for usertypes that may perform this action on any course */
		if ($acl->acl_check( 'action', $task, 'users', $my->usertype, $recordType, 'all' )) {
			return true;
		}

		/* Grant permission to request an unrestricted action. */
		if (!array_key_exists($task,$existingactions) && !array_key_exists($task,$newactions)) {
			return true;
		}

		/* If user is allowed to administer all, check to see that task is allowed */
		if (is_array($location_id) && $location_id[0] == 'all') {
			return true;
		}

		/**
		 * Must have a valid range of $locs to edit an existing record.
		 * If we fail on one, fail on all.
		 */
		if (is_array($location_id) && count($location_id)) {
			foreach ($location_id as $item) {
				if (@!array_key_exists($item,$locs)) {
					return false;
				}
			}
		}

		if (is_numeric($location_id) && intval($location_id)>0) {
			if (@$locs[0] == 'all') {
				return true;
			} else {
				return array_key_exists($location_id,$locs);
			}
		}

		/* Check to see that all tasks are allowed. */
		if ($acl->acl_check( 'action', 'all', 'users', $my->usertype, $recordType, 'own' )) {
			return true;
		}

		/* Check to see that the task is allowed. */
		if ($acl->acl_check( 'action', $task, 'users', $my->usertype, $recordType, 'own' )) {
			return true;
		}

		/* Return false for any rules not trapped. */
		return false;
	}
}
?>
