<?php

class A25_Listing_RowFormatterForListCourses extends A25_StrictObject
{
  protected $course;
  
  public function __construct(A25_DoctrineRecord $course) {
    $this->course = $course;
  }
  
  public function formatRow()
  {
		$return = array(
			'ID' => $this->course->course_id,
			'Type' => $this->course->typeName(),
			'Status' => $this->course->getStatus(),
      'Paid' => $this->paidIcon(),
			'Date/Time' => $this->dateTime(),
      'Published' => $this->publishedIcon(),
			'Instructors' => $this->instructors(),
			'Enrollments' => $this->enrollments(),
			'Location' => $this->course->getLocationName(),
		);
    
		return self::fireAppendFormatRow($return, $this->course);
  }
  
  protected function dateTime()
  {
    return '<a href="' . A25_Link::to(
					'/administrator/index2.php?option=com_course&task=viewA&id='
					. $this->course->course_id)
				. '">' . $this->course->getFormattedDateTime() . '</a>';
  }
  
  private function paidIcon()
  {
    if ($this->course->is_paid)
      return $this->greenDollar();
    else
      return '';
  }
  
  private function greenDollar()
  {
    return '<img src="'
        . A25_Link::to('/includes/js/ThemeOffice/dollar.png')
        . '" width="16" height="16" border="0" title="This course has been processed for payment."/>';
  }
  
  private function publishedIcon()
  {
    $img 	= $this->course->published ? 'tick.png' : 'publish_x.png';
    $alt 	= $this->course->published ? 'Published' : 'Unpublished';
    
    return '<img src="images/' . $img
        . '" width="12" height="12" border="0" alt="' . $alt . '" />';
  }
  
  private function instructors()
  {
    if ($this->course->instructor_id > 0) {
      $text = $this->course->Instructor->name;
      if ($this->course->instructor_2_id > 0)
        $text .= ', ' . $this->course->Instructor2->name;
    } else {
      $text = '<i>No Instructor Assigned</i>';
    }
    
    return $text;
  }
  
  private function enrollments()
  {
    return count($this->course->getSeatsTaken()) . '/'
        . $this->course->course_capacity;
  }
  
	private static function fireAppendFormatRow(array $formatRow,
      A25_DoctrineRecord $course)
	{
		foreach (A25_ListenerManager::all() as $listener)
		{
			if ($listener instanceof A25_ListenerI_AppendListCoursesFormatRow)
			{
				$formatRow = $listener->appendListCoursesFormatRow($formatRow,
						$course);
			}
		}
		return $formatRow;
	}
}
