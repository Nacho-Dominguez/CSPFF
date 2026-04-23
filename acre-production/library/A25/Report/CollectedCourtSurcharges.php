<?php

class A25_Report_CollectedCourtSurcharges extends A25_Report
{
	public function heading()
	{
		parent::heading();

		$total = 0.00;
		$total += $this->query()->select('SUM(unit_price) as total')
				->fetchOne()->total;
		echo "<h2>Total: $$total</h2>";
	}
  
	protected function formatRow(A25_DoctrineRecord $lineitem)
	{
		return array(
			'Date Paid' => $lineitem->date_paid,
			'Course Completed' => $lineitem->dateOfCompletedCourse(),
			'Student ID' => $lineitem->getStudent()->student_id,
			'First Name' => $lineitem->getStudent()->first_name,
			'Last Name' => $lineitem->getStudent()->last_name,
			'DOB'=> $lineitem->getStudent()->date_of_birth,
			'Referring Court' => $lineitem->getCourt()->court_name,
			'Amount' => '$'.$lineitem->faceValue()
		);
	}

	protected function query()
	{
		return Doctrine_Query::create()
			->from('A25_Record_OrderItem i')
			->leftJoin('i.Order o')
			->leftJoin('o.Enrollment e')
			->leftJoin('e.Student s')
			->leftJoin('e.Court c')
      ->leftJoin('e.Course course')
			->where('i.type_id = ?', A25_Record_OrderItemType::typeId_CourtSurcharge)
			->andWhere('i.calc_is_active = ?', true)
			->andWhere("i.date_paid >= ?", date('Y-m-d',$this->filter->from))
			->andWhere("i.date_paid <= ?", date('Y-m-d',$this->filter->to))
			->orderBy('i.date_paid')
			;
	}

	protected function name()
	{
		return 'Collected Court Surcharges';
	}
}