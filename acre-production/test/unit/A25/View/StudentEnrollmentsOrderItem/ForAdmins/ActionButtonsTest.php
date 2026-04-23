<?php
require_once(dirname(__FILE__) . '/../../../../../../autoload.php');

class ForAdmins_ActionButtonsTest_A25_View_StudentEnrollmentsOrderItem
		extends	A25_View_StudentEnrollmentsOrderItem_ForAdmins
{
	public function waiveLink()
	{
		return parent::waiveLink();
	}
	public function waiveLinkWith($waiveUnwaiveOrConfirm, $icon)
	{
		return parent::waiveLinkWith($waiveUnwaiveOrConfirm, $icon);
	}
	public function descriptiveWaiveLinkWith($action, $description, $icon)
	{
		return parent::descriptiveWaiveLinkWith($action, $description, $icon);
	}
	public function actionButtons()
	{
		return parent::actionButtons();
	}
}


class test_unit_A25_View_StudentEnrollments_ForAdmins_ActionButtonsTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function optimalPath()
	{
		$item = new A25_Record_OrderItem();
		
		$view = new
			ForAdmins_ActionButtonsTest_A25_View_StudentEnrollmentsOrderItem
					($item);

		$expected = '<td style="vertical-align: top; text-align: center;">'
				. $view->waiveLink() . '</td>'
				. '<td></td>';

		$this->assertEquals($expected, $view->actionButtons());
	}
	/**
	 * @test
	 */
	public function studentHasWaivedButNotConfirmedYet()
	{
		$item = new A25_Record_OrderItem();
		$item->waive(A25_Record_OrderItem::waiveType_Student_SelfSend);

		$view = new
			ForAdmins_ActionButtonsTest_A25_View_StudentEnrollmentsOrderItem
					($item);

		$expected = '<td style="vertical-align: top; text-align: center;">'
				. $view->descriptiveWaiveLinkWith('confirm',
						'Confirm student waiving of', 'confirm') . '</td>'
				. '<td>' . $view->waiveLinkWith('Unwaive', 'unwaive') . '</td>'
				. '<td></td>';
		
		$this->assertEquals($expected, $view->actionButtons());
	}
}
?>