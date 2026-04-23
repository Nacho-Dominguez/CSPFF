<?php
class Config_CourseTypes extends Config_Default_CourseTypes
{
  // Since CA-ADOD doesn't have high school courses, we are just overriding
  // these functions
	protected function standardHighSchoolWarning(A25_Record_Course $course)
	{
	}

	protected function standardHighSchoolActionColumnMessage()
	{
	}
}
