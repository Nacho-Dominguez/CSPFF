<?php

class A25_RowFormatter4IncomeReport extends A25_RowFormatter4FeeReports
{
	public function formatRow()
	{
		return array(
			'Type' => $this->orderItem->getTypeName(),
			'Student ID' => $this->studentLink(),
			'Enrollment ID' => $this->enrollLink(),
			'Course ID' => $this->courseLink(),
			'Amount' => $this->orderItem->chargeAmount(),
			'Accrual Date' => $this->orderItem->accrualDate(),
      'Date Paid' => $this->orderItem->date_paid,
		);
	}
}