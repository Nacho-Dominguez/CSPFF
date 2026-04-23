<?php
class Config_CourseTypes extends Config_Default_CourseTypes
{
	private $ClickProgram = 5;

	protected function actionColumnMessageText(A25_Record_Course $course)
	{
		if ($course->course_type_id == $this->ClickProgram)
			return 'only for the "Click Program"';
		else if ($course->course_type_id == self::HIGH_SCHOOL)
			return $this->standardHighSchoolActionColumnMessage();
	}

	public function restrictedEnrollmentWarning(A25_Record_Course $course)
	{
		if ($course->course_type_id == $this->ClickProgram)
			$text = 'NOTICE: This is a special class for the "Click Program" only.';
		else if($course->course_type_id == self::HIGH_SCHOOL)
			$text = $this->standardHighSchoolWarning($course);

		return $this->restrictionMessage($text);
	}
}
