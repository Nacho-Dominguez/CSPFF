<?php

class Controller_Api_NumberOfSeatsForLocation extends Controller
{
	public function executeTask()
	{
		A25_DoctrineRecord::$disableSave = true;
		
		$location_id = intval($_GET['id']);
		
		$location = A25_Record_Location::retrieve($location_id);
		
		echo $location->number_of_seats;
	}
}
