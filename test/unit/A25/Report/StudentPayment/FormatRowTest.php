<?php
class test_unit_FormatRowTest_StudentListPay
		extends A25_Report_StudentPayment
{
	public function __construct()
	{
	}
	public function formatRow(A25_DoctrineRecord $pay)
	{
		return parent::formatRow($pay);
	}
}

class test_unit_FormatRowTest_Pay
		extends A25_Record_Pay
{
	public function getPaymentTypeName()
	{
		return 'Cash';
    }
}

class test_unit_A25_Report_StudentListPay_FormatRowTest extends
		test_Framework_UnitTestCase
{
	/**
	 * A25_Record_Pay
	 */
	private $pay;
	private $expectedArray;

	public function setUp()
	{
		parent::setUp();

		$this->pay = new test_unit_FormatRowTest_Pay();
		$this->pay->pay_id = 12;
		$this->pay->amount = 1337;
		$this->pay->check_number = 1234;
		$this->pay->created = '05/26/2010';
		$this->pay->cc_trans_id = 4312;
		$this->pay->paid_by_name = 'mister';
		$this->pay->notes = 'This is a note';
		$this->pay->pay_type_id = A25_Record_Pay::typeId_Cash;

		$student = new A25_Record_Student();
		$student->last_name = 'Smith';
		$student->first_name = 'John';
		$student->date_of_birth = '1990-01-01';
		$student->address_1 = '123 fake st.';
		$student->city = 'Arvada';
		$student->state = 'CO';
		$student->zip = '80004';
		$student->home_phone = '111-111-1111';
		$this->pay->Student = $student;

		$user = new A25_Record_User();
		$user->id = 4; //greater than zero
		$user->name = 'Joe';
		$this->pay->CreatedBy = $user;

		$order = new A25_Record_Order();
		$this->pay->Order = $order;

		$enroll= new A25_Record_Enroll();
		$enroll->xref_id = 111111;
		$order->Enrollment = $enroll;


		$pay_id_link = '<a href="' .
			A25_Link::to(
					'/administrator/index2.php?option=com_pay&task=viewA&id='
					. $this->pay->pay_id)
			. '">' . $this->pay->pay_id . '</a>';

		$student = $this->pay->Student;
		$name = $student->firstLastName();
		$dob = date('m/d/Y', strtotime($student->date_of_birth));
		$address = $student->fullAddress()
				. '<br />';

		$this->expectedArray = array(
			'ID' => $pay_id_link,
			'Name' => $name,
			'DOB' => $dob,
			'Address' => $address,
			'Phone' => $student->home_phone,
			'Amount' => '$' . $this->pay->amount,
			'Check #' => $this->pay->check_number,
			'Paid Date' => date('m/d/Y', strtotime($this->pay->created)),
			'Paid Method' => 'Cash',
			'Transaction ID' => $this->pay->cc_trans_id,
			'Paid By' => $this->pay->paid_by_name,
			'Taken By' => $this->pay->CreatedBy->name,
			'Notes' => $this->pay->notes
		);


	}
	/**
	 * @test
	 */
	public function returnsExpectedArrayWithUser()
	{
		$report = new test_unit_FormatRowTest_StudentListPay();
		$this->assertEquals($this->expectedArray, $report->formatRow($this->pay));
	}
	/**
	 * @test
	 */
	public function returnsExpectedArrayWithoutUser()
	{
		$this->pay->created_by = 0;
		$this->expectedArray['Taken By'] = '';

		$report = new test_unit_FormatRowTest_StudentListPay();
		$this->assertEquals($this->expectedArray, $report->formatRow($this->pay));
	}
}
?>
