<?php

class Controller_Administrator_ExportDonations extends Controller
{
  public function executeTask()
  {
		$offset = intval($_GET['start']);
    
		$listing = new A25_Report_Donations(null, $offset);
    $listing->exportToExcel ();
  }
}
