<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_View_StudentEnrollmentsOrderItem_RunTest_StudentEnrollmentsOrderItem
		extends	A25_View_StudentEnrollmentsOrderItem
{
	public function value()
	{
		return parent::value();
	}
	public function actionButtons()
	{
		return parent::actionButtons();
	}
}


class test_unit_A25_View_StudentEnrollments_RunTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function optimalPath()
	{
		$item = new A25_Record_OrderItem();
		
		$view = new
			test_unit_A25_View_StudentEnrollmentsOrderItem_RunTest_StudentEnrollmentsOrderItem
					($item);

		$expected = '<tr>'
			. '<td style="vertical-align: top; text-align: right;" colspan="5">'
			. '<span style="font-weight: ; font-style: italic;">'
				. $item->getTypeName()
			. '</span></td>'
			. '<td style="vertical-align: top; text-align: center;">'
				. $view->value()
			. '</td>'
			. $view->actionButtons()
			. '</tr>';

		$this->assertEquals($expected, $view->run());
	}
}
?>
