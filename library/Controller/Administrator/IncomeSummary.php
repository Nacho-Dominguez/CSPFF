<?php

class Controller_Administrator_IncomeSummary extends Controller
{
  /**
   * @todo-soon - Remove duplication with Controller_MyInstructorsCourses 
   */
	public function executeTask()
	{
    if (!A25_DI::User()->isAdminOrHigher()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }
    
		$report = new A25_Report_IncomeSummary();
		$report->run();
	}
}
