<?php

/**
 * @todoSomeday - unit test this class
 */
class Controller_LocationInfo extends Controller
{
	/**
	 * @var A25_Record_Location
	 */
	private $location;
  private $id;
	
	public function __construct($request)
	{
    $this->id = intval($_GET['id']);
		$this->location = A25_Record_Location::retrieve($this->id);
		return parent::__construct($request);
	}
	
	protected function subtitle()
	{
		return 'Upcoming Courses at ' . $this->location->location_name;
	}
	
	public function executeTask()
	{
		$_REQUEST['Itemid'] = 19;
    
    $start = intval($_GET['start']);
    
		$report = new A25_Listing_LocationCourses($this->id, $start);
		$location = $this->location;

		require dirname(__FILE__) . '/LocationInfo.phtml';
	}
}
