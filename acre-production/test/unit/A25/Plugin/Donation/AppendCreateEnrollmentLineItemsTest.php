<?php

require_once ServerConfig::webRoot . '/plugins/Donation.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Plugin_Donation_AppendCreateEnrollmentLineItemsTest extends
		test_Framework_UnitTestCase
{
	public function setUp()
	{
		parent::setUp();
		
		set_include_path($this->original_include_path . PATH_SEPARATOR
				. ServerConfig::webRoot . '/plugins/Donation');
	}

	/**
	 * @test
	 */
	public function addsLineItemIfDonation()
	{
		$donation = $this->getMock('A25_Plugin_Donation', array('donationAmount'));
    $order = $this->getMock('A25_Record_Order');
    $donation->expects($this->any())
        ->method('donationAmount')
        ->will($this->returnValue(5));
    $order->expects($this->once())
        ->method('createLineItem')
        ->with(A25_Record_OrderItemType::typeId_Donation, 5);
    $donation->appendCreateEnrollmentLineItems($order);
	}

	/**
	 * @test
	 */
	public function addsNoLineItemIfNoDonation()
	{
		$donation = $this->getMock('A25_Plugin_Donation', array('donationAmount'));
    $order = $this->getMock('A25_Record_Order');
    $donation->expects($this->any())
        ->method('donationAmount')
        ->will($this->returnValue(0));
    $order->expects($this->never())
        ->method('createLineItem');
    $donation->appendCreateEnrollmentLineItems($order);
	}
}
