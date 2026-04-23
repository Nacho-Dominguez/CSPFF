<?php
require_once(dirname(__FILE__) . '/../../../../../../autoload.php');

class ForAdmins_WaiveLinkTest_A25_View_StudentEnrollmentsOrderItem
		extends	A25_View_StudentEnrollmentsOrderItem_ForAdmins
{
	public function waiveLink()
	{
		return parent::waiveLink();
	}
	public function editLink()
	{
		return parent::editLink();
	}

	public function waiveLinkWith($waiveOrUnwaive, $icon)
	{
		return parent::waiveLinkWith($waiveOrUnwaive, $icon);
	}
}


class test_unit_A25_View_StudentEnrollmentsOrderItem_ForAdmins_WaiveLinkTest extends
		test_Framework_UnitTestCase
{
	private $view;
	private $item;

	public function setUp()
	{
		parent::setUp();
		$this->item = new A25_Record_OrderItem();
		$this->item->item_id = 45;
		$this->item->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
		$this->item->Order = new A25_Record_Order();

		$user = new A25_Record_User();
		$user->usertype = 'Super Administrator';
		A25_DI::setUser($user);

		$this->view = new
			ForAdmins_WaiveLinkTest_A25_View_StudentEnrollmentsOrderItem
					($this->item);
	}
	/**
	 * @test
	 */
	public function showsMinus_whenItemIsNotWaived()
	{
		$expectedLink = $this->view->waiveLinkWith('Waive', '&#8212;');

		$this->expect($expectedLink);
	}
	/**
	 * @test
	 */
	public function showsPlus_whenItemIsWaived()
	{
		$this->item->waive();

		$expectedLink = $this->view->waiveLinkWith('Unwaive', '+');

		$this->expect($expectedLink);
	}
	/**
	 * @test
	 */
	public function showsChangeLink_whenItemIsTuition()
	{
		$this->item->type_id = A25_Record_OrderItemType::typeId_CourseFee;

		$expectedLink = $this->view->editLink();

		$this->expect($expectedLink);
	}

	private function expect($expected)
	{
		$this->assertEquals($expected, $this->view->waiveLink());
	}

}
?>
