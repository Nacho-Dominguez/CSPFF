<?php

class Controller_Administrator_ListCourses extends Controller
{
  /**
   * @todo-soon - Remove duplication with Controller_MyInstructorsCourses 
   */
	public function executeTask()
	{
    if (A25_DI::User()->isCourtAdministrator()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }
    
		$offset = intval($_GET['start']);

		$listing = new A25_Listing_Courses(null, $offset);
		$listing->run();
    
    $_SESSION['last_search'] = $_SERVER['REQUEST_URI'];
	}
}
