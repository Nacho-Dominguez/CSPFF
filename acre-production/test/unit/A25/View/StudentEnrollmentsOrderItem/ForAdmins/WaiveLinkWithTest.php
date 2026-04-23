<?php
require_once(dirname(__FILE__) . '/../../../../../../autoload.php');

class ForAdmins_WaiveLinkWithTest_A25_View_StudentEnrollmentsOrderItem
		extends	A25_View_StudentEnrollmentsOrderItem_ForAdmins
{
	public function waiveLinkWith($waiveOrUnwaive, $icon)
	{
		return parent::waiveLinkWith($waiveOrUnwaive, $icon);
	}
}


class test_unit_A25_View_StudentEnrollments_ForAdmins_WaiveLinkWithTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function returnsExpectedHtml()
	{
		$item = new A25_Record_OrderItem();
		$item->item_id = 67;
		$item->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
		$icon = '+';
		$expected = '<a href="javascript:if(confirm(\'Waive Court Surcharge?\'))'
			. ' location=\'index2.php?option=com_student&task=waiveOrderItem'
			. '&item_id=' . $item->item_id . '\'">'
			. '<span style="color: blue; font-weight: bolder;">' . $icon
			. '</span></a>';

		$view = new ForAdmins_WaiveLinkWithTest_A25_View_StudentEnrollmentsOrderItem($item);

		$actual = $view->waiveLinkWith('Waive', $icon);

		$this->assertEquals($expected, $actual);
	}
}
?>
