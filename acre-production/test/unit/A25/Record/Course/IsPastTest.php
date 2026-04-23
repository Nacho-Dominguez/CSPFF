<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Course_IsPastTest extends test_Framework_UnitTestCase {
    /**
	 * @test
	 */
	public function itHas()
	{
		$courseRecord = new A25_Record_Course();
		$courseRecord->setCourseTime(strtotime('-1 days'));
		$this->assertTrue($courseRecord->isPast());
    }
	/**
	 * @test
	 */
	public function itHasNot()
	{
		$courseRecord = new A25_Record_Course();
		// Technically, +0 seconds should work most of the time.  However, the
		// test occasionally fails if it happens to roll over the second.
		$courseRecord->setCourseTime(strtotime('+1 second'));
		$this->assertFalse($courseRecord->isPast());
    }
}
?>
