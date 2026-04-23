<?php

class Controller_Administrator_ExportFundDonations extends Controller
{
  public function executeTask()
  {
		$offset = intval($_GET['start']);

		$listing = new A25_Report_FundDonations(null, $offset);
    $listing->exportToExcel ();
  }
}
