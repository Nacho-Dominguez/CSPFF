<?php

class Controller_PrivateCourse extends Controller
{
	public function executeTask()
	{
		$course = A25_Record_Course::retrieve(intval($_GET['id']));
        if ($course) {
            $org_id = $course->getSetting('organization_id');
        } else {
            A25_DI::Redirector()->redirectBasedOnSiteRoot('/find-a-course', 'Course not found. Please select a different course.');
        }
		$org = Doctrine::getTable('JosOrganization')->find($org_id);
		require dirname(__FILE__) . '/PrivateCourse.phtml';
	}
}
