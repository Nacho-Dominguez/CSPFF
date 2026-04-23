<?php

class Controller_Administrator_ListCoursesOfTrainees extends Controller
{
  /**
   * @todo-soon - Remove duplication with Controller_ListCourses 
   */
	public function executeTask()
	{
		$offset = intval($_GET['start']);

		$listing = new A25_Listing_MyInstructorsCourses(null, $offset);
		$listing->run();
	}
}
