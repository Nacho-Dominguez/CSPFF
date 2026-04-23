<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Enroll_ReplaceBodyTagsTest_Enroll extends
		A25_Record_Enroll
{
	public function getLateFee()
	{
		return 10;
	}
	public function infoForEmail()
	{
		return 'Stubbing output for showEnrollment()';
	}
}

class test_unit_A25_Record_Enroll_ReplaceBodyTagsTest extends
		test_Framework_UnitTestCase
{
	private $enroll;
	
	public function setUp()
	{
		parent::setUp();
		$this->enroll = new test_unit_A25_Record_Enroll_ReplaceBodyTagsTest_Enroll();
		$this->enroll->Student = new A25_Record_Student();
	}
	/**
	 * @test
	 */
	public function ChangesStatusOfEnrollment()
	{
		$this->enroll->xref_id = 5676;
		$this->enroll->Course = new A25_Record_Course();
		$this->enroll->Course->Location = new A25_Record_Location();

		$body = $this->enroll->replaceBodyTags('!ENROLLMENT!');

		$this->assertEquals($this->enroll->infoForEmail(),
					$body);
	}
	/**
	 * @test
	 */
	public function ChangesFee()
	{
		$body = $this->enroll->replaceBodyTags('!FEE!');

		$this->assertEquals(number_format($this->enroll->Student->getAccountBalance(),2),
					$body);
	}
	/**
	 * @test
	 */
	public function ChangesLateFee()
	{
		$body = $this->enroll->replaceBodyTags('!LATE_FEE!');

		$this->assertEquals($this->enroll->getLateFee(),$body);
	}
	/**
	 * @test
	 */
	public function ChangesSurchargeToFootnote()
	{
		$order = new A25_Record_Order();
		$this->enroll->Order = $order;

		$item = new A25_Record_OrderItem();
		$item->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
		$item->quantity = 1;
		$item->unit_price = 25;
		$order->OrderItems[] = $item;

		$body = $this->enroll->replaceBodyTags('!SURCHARGE!');

		$expected = 'This amount includes a $'
				. $item->unit_price . ' DOR surcharge.  '
				. PlatformConfig::surchargeFootnote($item->unit_price);

		$this->assertEquals($expected,$body);
	}
	/**
	 * @test
	 */
	public function ChangesSurchargeToMessageAboutWaiverForm()
	{
		$order = new A25_Record_Order();
		$this->enroll->Order = $order;

		$item = new A25_Record_OrderItem();
		$item->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
		$item->quantity = 1;
		$item->unit_price = 25;
		$item->waive(A25_Record_OrderItem::waiveType_Student_SelfSend);
		$order->OrderItems[] = $item;

		$body = $this->enroll->replaceBodyTags('!SURCHARGE!');

		$expected = '<b>You indicated that your referring court gave you a form to '
			. 'waive the DOR surcharge.  Because you have not been charged the DOR '
			. 'surcharge, the form must be submitted to the Alive at 25 '
			. 'office in order to receive credit for the course.</b>  '
			. PlatformConfig::surchargeFootnote($item->unit_price);

		$this->assertEquals($expected,$body);
	}
	/**
	 * @test
	 */
	public function ChangesSurchargeToEmptyString()
	{
		$this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_Insurance;

		$body = $this->enroll->replaceBodyTags('!SURCHARGE!');

		$this->assertEquals('',$body);
	}
	/**
	 * @test
	 */
	public function ChangesContactUs()
	{
		$body = $this->enroll->replaceBodyTags('!CONTACT!');

		$this->assertEquals(PlatformConfig::contactUs(),$body);
	}
	/**
	 * @test
	 */
	public function ChangesAccountInfo()
	{
		$this->enroll->Student->first_name = 'John';

		$body = $this->enroll->replaceBodyTags('!ACCOUNT_INFO!');

		$this->assertEquals(A25_Html::studentAccountInformation($this->enroll->Student),$body);
	}
}
