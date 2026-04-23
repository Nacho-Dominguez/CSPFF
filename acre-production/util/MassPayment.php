<?php

abstract class util_MassPayment
{
	protected $course_ids;
	protected $amountToPay;
	
	abstract protected function payTypeId();
	abstract protected function applyCustom(A25_Record_Pay $pay);
	
	public function execute()
	{
		A25_DI::setUserId(62);

		test_HelperFunctions::createJoomlaDatabaseObject();

		foreach ($this->course_ids as $course_id) {
			echo "Checking course #$course_id\n";
			$course = A25_Record_Course::retrieve($course_id);
			foreach ($course->Enrollments as $enroll)
			{
				if (!$enroll->isActive())
					continue;

				$student = $enroll->Student;
				$balance = $student->getAccountBalance();
				if ($balance <= 0)
					continue;

				echo "Checking enrollment #$enroll->xref_id\n";

				if ($enroll->Order->totalAmount() != $this->amountToPay)
					throw new Exception('Order amount was unexpected: $'
							. $enroll->Order->totalAmount());
				
				if ($balance != $this->amountToPay)
					echo 'WARNING: Account balance was unexpected: $'
							. $balance . "\n";

				$pay = new A25_Record_Pay();
				$pay->Enrollment = $enroll;
				$pay->Order = $enroll->Order;
				$pay->Student = $student;
				$pay->amount = $this->amountToPay;
				$pay->pay_type_id = $this->payTypeId();

				$this->applyCustom($pay);

				$pay->save();

				$student->updateOrdersAndEnrollmentsAfterPayment();
			}
		}
	}
}
