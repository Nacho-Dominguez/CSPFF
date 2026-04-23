<?php
/**
 * Since new types are likely to be added more often than new functions, an OO
 * design would be more appropriate than this.  For example, there should be a
 * class for High School that implements the necessary functions, and then this
 * object could just have a collection of CourseTypes that it loops through
 * until 1 of them returns the correct text. 
 */
class Config_Default_CourseTypes extends A25_StrictObject
{
	const PUBLIC_COURSE = 1;
	const HIGH_SCHOOL = 2;
	const SPANISH = 3;
	
	public function actionColumn(A25_Record_Course $course)
	{
		$text = $this->actionColumnMessageText($course);
		if ($text)
			return $this->actionColumnMessage($text);
	}

	protected function actionColumnMessage($text)
	{
		return '<div style="font-size: 10px; color: #770000;">(' . $text
				. ')</div>';
	}

	protected function actionColumnMessageText(A25_Record_Course $course)
	{
		if ($course->course_type_id == self::HIGH_SCHOOL)
			return $this->standardHighSchoolActionColumnMessage();
		if ($course->course_type_id == self::SPANISH)
			return $this->standardSpanishActionColumnMessage();
	}

	public function restrictedEnrollmentWarning(A25_Record_Course $course)
	{
		$text = $this->restrictedMessageText($course);
		if ($text)
			return $this->restrictionMessage($text);
	}

	protected function restrictedMessageText($course)
	{
		if($course->course_type_id == self::HIGH_SCHOOL) {
			return $this->restrictionMessage(
					$this->standardHighSchoolWarning($course));
		}
		if($course->course_type_id == self::SPANISH) {
			return $this->restrictionMessage(
					$this->standardSpanishWarning($course));
		}
	}
	
	protected function restrictionMessage($text)
	{
		return "<p class='required' style='background-color: yellow;'>$text</p>";
	}

	protected function standardHighSchoolWarning(A25_Record_Course $course)
	{
		return 'NOTICE: This is a special, private course for students of <i>'
				. $course->getLocationName()
				. '</i>. If you do not attend this school,
				please <a href="'. PlatformConfig::findACourseUrl()
				. '">choose a different course</a>.';
	}

	protected function standardSpanishWarning(A25_Record_Course $course)
	{
		return 'NOTICE: This course will be taught in Spanish. If you do not speak fluent Spanish,
				please <a href="'. PlatformConfig::findACourseUrl()
				. '">choose a different course</a>.';
	}

	protected function standardHighSchoolActionColumnMessage()
	{
		return 'This class is only for students of this school';
	}

	protected function standardSpanishActionColumnMessage()
	{
		return 'This class will be in Spanish. Esta clase ser&aacute; en espa&ntilde;ol.';
	}
}
