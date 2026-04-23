<?php
require_once(dirname(__FILE__) . '/../../../../../../autoload.php');

class ForAdmins_EditLinkTest_A25_View_StudentEnrollmentsOrderItem
		extends	A25_View_StudentEnrollmentsOrderItem_ForAdmins
{
	public function editLink()
	{
		return parent::editLink();
	}
}


class test_unit_A25_View_StudentEnrollmentsOrderItem_ForAdmins_EditLinkTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function returnsExpectedHtml()
	{
		$user = new A25_Record_User();
		$user->usertype = 'Super Administrator';
		A25_DI::setUser($user);

		$item = new A25_Record_OrderItem();
		$item->item_id = 67;
		$item->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
		$expected = '<a href="index2.php?option=com_student&task=editItemAmount&item_id='
			. $item->item_id
			. '"><span style="color: blue; font-weight: bolder;">edit</span></a>';

		$view = new ForAdmins_EditLinkTest_A25_View_StudentEnrollmentsOrderItem($item);

		$actual = $view->editLink();

		$this->assertEquals($expected, $actual);
	}
	/**
	 * @test
	 */
	public function returnsNothingIfNotSuperAdmin()
	{
		$user = new A25_Record_User();
		$user->usertype = 'Administrator';
		A25_DI::setUser($user);

		$item = new A25_Record_OrderItem();
		$item->item_id = 67;
		$item->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
		$expected = '';

		$view = new ForAdmins_EditLinkTest_A25_View_StudentEnrollmentsOrderItem($item);

		$actual = $view->editLink();

		$this->assertEquals($expected, $actual);
	}
}
?>
