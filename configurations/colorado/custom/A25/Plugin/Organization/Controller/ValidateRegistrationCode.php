<?php

class Controller_ValidateRegistrationCode extends Controller
{
	public function executeTask()
	{
    $course_id = (int) $_REQUEST['course'];
		$course = A25_Record_Course::retrieve($course_id);
		$org_id = $course->getSetting('organization_id');
		$org = Doctrine::getTable('JosOrganization')->find($org_id);
		if ($_REQUEST['password'] == $org->password)
			A25_DI::Redirector()->redirect(
					"/component/option,com_course/task,confirm/course_id,$course->course_id/Itemid,19/");
		else
			A25_DI::Redirector()->redirectBasedOnSiteRoot(
					"/private-course?id=$course->course_id&tryagain=1");
	}
}
