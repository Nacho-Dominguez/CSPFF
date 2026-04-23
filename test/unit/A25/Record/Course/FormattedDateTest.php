<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Course_GetDateTest extends test_Framework_UnitTestCase {
    /**
	 * @test
	 */
	public function returnsFalseIfNotSetYet()
	{
		$courseRecord = new A25_Record_Course();
		$courseRecord->course_start_date = null;
		$this->assertEquals(false, $courseRecord->formattedDate('course_start_date',
				'M d, Y h:i A'));
    }
    /**
	 * @test
	 */
	public function returnsFalseIfAllZeroes()
	{
		$courseRecord = new A25_Record_Course();
		$courseRecord->course_start_date = '0000-00-00 00:00:00';
		$this->assertEquals(false, $courseRecord->formattedDate('course_start_date',
				'M d, Y h:i A'));
    }
    /**
	 * @test
	 */
	public function returnsFormatIfDefined()
	{
		$courseRecord = new A25_Record_Course();
		$courseRecord->course_start_date = '2010-10-01 04:00:00';
		$this->assertEquals('Oct 01, 2010 04:00 AM', $courseRecord->formattedDate('course_start_date',
				'M d, Y h:i A'));
    }
}
?>
