<?php
class A25_Query extends Doctrine_Query
{
	public function andCourseStartsWithin(A25_ReportFilter $filter)
	{
		return $this->andFieldFallsWithin($filter,'c.course_start_date');
	}
	public function andOrderItemCreatedWithin(A25_ReportFilter $filter)
	{
		return $this->andFieldFallsWithin($filter,'i.created');
	}
	private function andFieldFallsWithin(A25_ReportFilter $filter, $fieldname)
	{
		return $this->andWhere("$fieldname > ?", date('Y-m-d',$filter->from))
			->andWhere("$fieldname < ?", A25_Functions::addADay($filter->to));
	}
  public function andFeeIsDeferredRevenueForUpcomingCourse()
  {
    return $this->andWhere('i.date_paid IS NOT NULL')
      ->andWhere('i.calc_is_active = 1')
      ->andWhere('i.calc_accrual_date IS NULL')
      ->andWhereNotIn('i.type_id', A25_Record_OrderItem::neverRevenueList());
  }
}