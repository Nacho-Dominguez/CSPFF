<?php

class Controller_Administrator_Reports extends Controller
{
	public function executeTask()
	{
		require dirname(__FILE__) . '/Reports.phtml';
	}
  
  private static function fireAppendToIndividualRecordReports()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_Reports)
        $listener->appendToIndividualRecordReports ();
    }
  }
}


