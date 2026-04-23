<?php

class Controller_Administrator_ListFundDonations extends Controller
{
    public function executeTask()
    {
        $offset = intval($_GET['start']);
        $listing = new A25_Report_FundDonations(null, $offset);
        $listing->run();
    }
}
