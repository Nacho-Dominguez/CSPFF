<?php
class Config_CourseTypes extends Config_Default_CourseTypes
{
  // Since CA-ADOD doesn't have high school courses, we are just overriding
  // these functions
	protected function standardHighSchoolWarning(A25_Record_Course $course)
	{
		return 'NOTICE: This is a special course for students age 18 and younger. If you are over 18,
				please <a href="'. PlatformConfig::findACourseUrl()
				. '">choose a different course</a>.';
	}

	protected function standardHighSchoolActionColumnMessage()
	{
		return 'This class is only for students age 18 and younger referred by the Santa Clara or San Mateo Juvenile Courts';
	}
}
