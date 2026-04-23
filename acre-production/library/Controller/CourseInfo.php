<?php

class Controller_CourseInfo extends Controller
{
  private $course;
  
	public function __construct($request)
	{
    $this->id = intval($_GET['course_id']);
		$this->course = A25_Record_Course::retrieve($this->id);
		return parent::__construct($request);
	}
  
  public function executeTask()
  {
    $page = new A25_View_Student_CourseInfo($this->course);
    $page->render();
  }
}
