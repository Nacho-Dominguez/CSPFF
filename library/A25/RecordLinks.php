<?php

class A25_RecordLinks
{
	public static function linkToTask($querystring, $id)
	{
		return "<a href='" . A25_Link::to(
				"/administrator/index2.php?" . $querystring . $id) . "'>$id</a>";
	}
  
  public static function studentLink($student_id)
  {
    return A25_RecordLinks::linkToTask('option=com_student&task=viewA&id=',
			 $student_id);
  }
	
  public static function enrollLink($xref_id)
  {
    return A25_RecordLinks::linkToTask(
			'option=com_student&task=enrollview&xref_id=',
			$xref_id);
  }
  
  public static function courseLink($course_id)
  {
    return A25_RecordLinks::linkToTask('option=com_course&task=viewA&id=',
			$course_id);
  }
}