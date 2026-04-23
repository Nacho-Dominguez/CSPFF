<?php

class A25_Form_Validate_InstructorCourseStartDate extends Zend_Validate_Abstract
{
    const INSTRUCTOR30DAYS = 'instructor30days';

    protected $_messageTemplates = array(self::INSTRUCTOR30DAYS =>
			'Instructors cannot set the course start time within 30 days.  Please contact the main office if you need this course within 30 days.');

	private $course_start_date;
	public function __construct($course_start_date)
	{
		$this->course_start_date = $course_start_date;
	}

	/**
	* Check if the element using this validator is valid
	*
	* @param $value string
	* @return boolean Returns true if the element is valid
	*/
	public function isValid($value)
	{
		$this->_setValue($value);

		$earliestAllowedDate = strtotime(
				PlatformConfig::instructorClassCreationDeadline . ' days');

		if ($this->course_start_date)
			$originalDate = strtotime($this->course_start_date);
		else
			$originalDate = false;

		$newDate = strtotime($value);

		if ($originalDate != $newDate && A25_DI::User()->isInstructor()) {
			// Cannot put new course date before deadline:
			if ($newDate < $earliestAllowedDate) {
				$this->_error(self::INSTRUCTOR30DAYS);
				return false;
			// Cannot change course date if it is already within deadline:
			} else if ($originalDate && $originalDate < $earliestAllowedDate) {
				$this->_error(self::INSTRUCTOR30DAYS);
				return false;
			}
		}
		return true;
	}
}
