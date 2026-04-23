<?php

class Controller_Broomfield extends Controller
{
	protected function subtitle()
	{
		return 'Upcoming Courses for Broomfield Municipal Court';
	}
	
	public function executeTask()
	{
		$_REQUEST['Itemid'] = 19;
        $start = intval($_GET['start']);

		$report = new A25_Listing_BroomfieldCourses($start);
		require dirname(__FILE__) . '/Broomfield.phtml';
	}
}
