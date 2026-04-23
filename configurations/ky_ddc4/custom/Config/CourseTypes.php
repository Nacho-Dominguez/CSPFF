<?php
class Config_CourseTypes extends Config_Default_CourseTypes
{
	const Voluntary = 5;

	protected function actionColumnMessageText(A25_Record_Course $course)
	{
		if ($course->course_type_id == self::Voluntary)
			return 'Voluntary students only, no court or deferment students allowed';
		else if ($course->course_type_id == self::HIGH_SCHOOL)
			return $this->standardHighSchoolActionColumnMessage();
	}

	public function restrictedEnrollmentWarning(A25_Record_Course $course)
	{
		if ($course->course_type_id == self::Voluntary)
			$text = 'NOTICE: This class is for voluntary students only. If you '
                . 'are taking the class because of a court order or deferment, '
                . 'please <a href="'. PlatformConfig::findACourseUrl()
				. '">choose a different class</a>.';
		else if($course->course_type_id == self::HIGH_SCHOOL)
			$text = $this->standardHighSchoolWarning($course);

		return $this->restrictionMessage($text);
	}
}
