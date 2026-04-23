<?php

class A25_Remind_Students_KickOut extends A25_Remind_Students
{
  protected function markSent($enroll)
  {
    $enroll->kickOut();
  }
  
  protected function subject()
  {
    return 'Seat reservation expired';
  }
  
  protected function body(A25_Record_Enroll $enroll)
  {
    ob_start();
    require dirname(__FILE__) . '/KickOutBody.phtml';
    return ob_get_clean();
  }
  
	protected function whom()
	{
		$q = Doctrine_Query::create()
			->select('*')
			->from('A25_Record_Enroll e')
      ->innerJoin('e.Course c')
			->leftJoin('e.Student s')
      ->where('e.kick_out_date < ?', A25_Functions::formattedDateTime())
      ->andWhere('c.course_start_date > ?', self::courseAfter())
      ->andWhereIn('e.status_id', A25_Record_Enroll::reservationIsTemporaryStatusList());
		return $q->execute();
	}
  
  private static function courseAfter()
  {
    return A25_Functions::formattedDateTime();
  }
}
