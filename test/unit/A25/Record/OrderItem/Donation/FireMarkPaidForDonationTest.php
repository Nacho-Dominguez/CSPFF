<?php

class test_unit_A25_Record_OrderItem_Donation_FireMarkPaidForDonationTest
    extends test_Framework_UnitTestCase
{
	public function tearDown()
	{
    A25_ListenerManager::destroy();
    A25_ListenerManager::startUp();
	}
  
	/**
	 * @test
	 */
	public function marksPaid()
	{
    $fee = new A25_Record_OrderItem_Donation();
    $plugin = $this->getMock('A25_Plugin_Donation', array('appendMarkPaidForDonation'));
    $plugin->expects($this->once())->method('appendMarkPaidForDonation')
        ->with($fee);
    A25_ListenerManager::startUpWithListeners(array($plugin));
    $fee->markPaid();
	}
}
