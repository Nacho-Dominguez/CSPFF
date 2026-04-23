<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');
class test_unit_A25_Record_OrderItem_NonCourseRevenueTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function onlyPath()
	{
		$this->assertEquals('i.type_id <> ' . A25_Record_OrderItemType::typeId_CourseFee,
				A25_Record_OrderItem::nonCourseRevenue('i'));
	}
}
?>
