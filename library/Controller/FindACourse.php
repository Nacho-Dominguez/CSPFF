<?php

/**
 * @todoSomeday - unit test this class
 */
class Controller_FindACourse extends Controller
{
	protected function subtitle()
	{
		return 'Upcoming Courses in ' . PlatformConfig::STATE_NAME;
	}
	
	public function executeTask()
	{
		if(PlatformConfig::isNationalPortal)
			A25_DI::Redirector()->redirect(A25_Link::to(PlatformConfig::findACourseUrl()));

		$_REQUEST['Itemid'] = 19;
    
    $zip = intval($_GET['zip']);
    if (!$zip > 0)
      $zip = null;
    $radius = intval($_GET['radius']);
    $start = intval($_GET['start']);

		$report = new A25_Listing_BrowseCourses($zip, $radius, $start);
		require dirname(__FILE__) . '/FindACourse.phtml';
	}
}
