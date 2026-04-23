<?php

require_once ServerConfig::webRoot . '/plugins/Donation.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Plugin_Donation_AppendMarkPaidForDonationTest extends
		test_Framework_UnitTestCase
{
  private $donation;
  private $receipt;
  private $factory;
	
	public function setUp()
	{
		parent::setUp();
		
		set_include_path($this->original_include_path . PATH_SEPARATOR
				. ServerConfig::webRoot . '/plugins/Donation');
    
    $this->donation = new A25_Plugin_Donation();
    
    $this->receipt = $this->mock('A25_Envelope');
    
    $this->factory = $this->mock('A25_Factory');
    $this->factory->expects($this->any())->method('DonationReceipt')
        ->will($this->returnValue($this->receipt));
    
    A25_DI::setFactory($this->factory);
	}

	/**
	 * @test
	 */
	public function sendsEmailIfEntered()
	{
		$fee = new A25_Record_OrderItem_Donation();
    $_POST['x_email'] = 'test@test.com';
    
    $this->receipt->expects($this->once())->method('send')
        ->with($_POST['x_email']);
    $this->donation->appendMarkPaidForDonation($fee);
	}

	/**
	 * @test
	 */
	public function sendsToStudentIfNotEntered()
	{
		$fee = $this->getMock('A25_Record_OrderItem_Donation', array('getStudent'));
    $student = new A25_Record_Student();
    $student->email = 'student@test.com';
    
    $fee->expects($this->any())->method('getStudent')
        ->will($this->returnValue($student));
    
    $this->receipt->expects($this->once())->method('send')
        ->with($student->email);
    $this->donation->appendMarkPaidForDonation($fee);
	}

	/**
	 * @test
	 */
	public function sendsNoEmailIfNotEnteredAndNoStudent()
	{
		$fee = $this->getMock('A25_Record_OrderItem_Donation', array('getStudent'));
    
    $this->receipt->expects($this->never())->method('send');
    $this->donation->appendMarkPaidForDonation($fee);
	}
}
