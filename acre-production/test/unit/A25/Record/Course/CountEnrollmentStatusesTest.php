<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Course_CountEnrollmentStatusesTest
		extends test_Framework_UnitTestCase
{
	private $course;
	private $enroll;

	public function setup()
	{
		$this->course = new A25_Record_Course();
		parent::setUp();
	}
    /**
	 * @test
	 */
	public function returnsOneCancelled_whenCancelledEnrollment()
	{
		$this->createEnrollmentOfStatusId(A25_Record_Enroll::statusId_canceled);

		$this->checkForNumberInStatus(1,'Cancelled');
    }
    /**
	 * @test
	 */
	public function returnsOneCompleted_whenCompletedEnrollment()
	{
		$this->createEnrollmentOfStatusId(A25_Record_Enroll::statusId_completed);

		$this->checkForNumberInStatus(1,'Completed');
    }
    /**
	 * @test
	 */
	public function returnsOneFailed_whenFailedEnrollment()
	{
		$this->createEnrollmentOfStatusId(A25_Record_Enroll::statusId_failed);

		$this->checkForNumberInStatus(1,'Failed');
    }
    /**
	 * @test
	 */
	public function returnsOnePaidNoShow_whenPaidNoShowEnrollment()
	{
		$this->createEnrollmentOfStatusId(A25_Record_Enroll::statusId_noShow);
		$this->enroll->Order = new A25_Record_Order();
		$item = $this->enroll->Order->createLineItem(A25_Record_OrderItemType::typeId_CourseFee, 10);
    $item->markPaid();

		$this->checkForNumberInStatus(1,'Paid No Show');
    }
    /**
	 * @test
	 */
	public function returnsOneNotPaidNoShow_whenNotPaidNoShowEnrollment()
	{
		$this->createEnrollmentOfStatusId(A25_Record_Enroll::statusId_noShow);
		$this->enroll->Order = new A25_Record_Order();
		$this->enroll->Order->createLineItem(A25_Record_OrderItemType::typeId_CourseFee, 10);

		$this->checkForNumberInStatus(1,'Unpaid No Show');
    }
    /**
	 * @test
	 */
	public function returnsOnePending_whenPendingEnrollment()
	{
		$this->createEnrollmentOfStatusId(A25_Record_Enroll::statusId_pending);

		$this->checkForNumberInStatus(1,'Pending');
    }
    /**
	 * @test
	 */
	public function returnsOneRegistered_whenRegisteredEnrollment()
	{
		$this->createEnrollmentOfStatusId(A25_Record_Enroll::statusId_registered);

		$this->checkForNumberInStatus(1,'Registered');
    }
    /**
	 * @test
	 */
	public function returnsOneStudent_whenStudentEnrollment()
	{
		$this->createEnrollmentOfStatusId(A25_Record_Enroll::statusId_student);

		$this->checkForNumberInStatus(1,'Student');
    }

	private function createEnrollmentOfStatusId($status_id)
	{
		$this->enroll = new A25_Record_Enroll();
		$this->enroll->status_id = $status_id;
		$this->course->Enrollments[] = $this->enroll;
	}

	private function checkForNumberInStatus($expected,$status)
	{
		$stats = $this->course->countEnrollmentStatuses();
		$this->assertEquals($expected,$stats[$status]);
	}
}
?>
