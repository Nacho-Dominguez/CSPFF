<?php

/**
 * @deprecated - Use Zend_Form_Element_Select with a Doctrine statement instead.
 * See A25_Filter_EnrollmentStatus for an example.
 */
class A25_SelectListGenerator
{
    public static function generateStateSelectList($fieldName, $fieldAttributes, $selectedState)
	{
		$states = array();
		$states[] = mosHTML::makeOption('','- Select State -');
		$sql = "SELECT `state_code` AS value, `state_name` AS text FROM #__us_state ORDER BY `state_name`;";
		A25_DI::DB()->setQuery($sql);
		$states = array_merge($states,A25_DI::DB()->loadObjectList());
		return mosHTML::selectList( $states, $fieldName, $fieldAttributes, 'value', 'text', $selectedState);
    }
	public static function generateLicenseStatusSelectList($fieldName, $fieldAttributes, $selectedStatus)
	{
		$license_status = array();
		$license_status[] = mosHTML::makeOption('','- Select One -');
		$sql = "SELECT `status_id` AS value, `status_name` AS text FROM #__license_status;";
		A25_DI::DB()->setQuery($sql);
		$license_status = array_merge($license_status,A25_DI::DB()->loadObjectList());
		return mosHTML::selectList( $license_status, $fieldName, $fieldAttributes, 'value', 'text', $selectedStatus);

	}
	public static function generateCourtSelectList($fieldName, $fieldAttributes, $selectedStatus)
	{
		$court = array();
		$court[] = mosHTML::makeOption('','- Select Referring Court -');
		$sql = "SELECT `court_id` AS value, CONCAT(`state`,' - ',SUBSTR(`court_name`,1,35)) AS text FROM #__court WHERE published=1 ORDER BY `state`,`court_name`;";
		A25_DI::DB()->setQuery($sql);
		$court = array_merge($court,A25_DI::DB()->loadObjectList());
		return mosHTML::selectList($court, $fieldName, $fieldAttributes, 'value', 'text', $selectedStatus);
	}
	public static function generateEnrollmentStatusSelectList($fieldName, $fieldAttributes, $selectedStatus)
	{
		$enrollment_status = array();
		$enrollment_status[] = mosHTML::makeOption('','- Select One -');
		$sql = "SELECT `status_id` AS value, `status_name` AS text FROM #__enroll_status;";
		A25_DI::DB()->setQuery($sql);
		$enrollment_status = array_merge($enrollment_status,A25_DI::DB()->loadObjectList());
		return mosHTML::selectList( $enrollment_status, $fieldName, $fieldAttributes, 'value', 'text', $selectedStatus);

	}

	public static function generateCourseStatusSelectList($fieldName, $selectedStatus = '', $fieldAttributes = '')
	{
		// build list of status
		$status = array();
		$status[] = mosHTML::makeOption('','- Select Course Status -');
		$courseStatuss = Doctrine::getTable('JosCourseStatus')->findAll();
		foreach ($courseStatuss as $courseStatus) {
			$status[] = mosHTML::makeOption($courseStatus->status_id, $courseStatus->status_name);
		}

		return mosHTML::selectList( $status, $fieldName, $fieldAttributes, 'value', 'text', $selectedStatus);
	}

	public static function generateCourseTypeSelectList($fieldName, $selectedType = '', $fieldAttributes = '')
	{
		// build list of status
		$types = array();
		$types[] = mosHTML::makeOption('','- Select Course Type -');
		$courseTypes = Doctrine::getTable('JosCourseType')->findAll();
		foreach ($courseTypes as $courseType) {
			$types[] = mosHTML::makeOption($courseType->type_id, $courseType->type_name);
		}

		return mosHTML::selectList( $types, $fieldName, $fieldAttributes, 'value', 'text', $selectedType);
	}
}
?>
