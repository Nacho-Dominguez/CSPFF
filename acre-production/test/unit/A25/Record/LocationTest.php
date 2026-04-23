<?php
require_once(dirname(__FILE__) . '/../../../../autoload.php');
define('_VALID_MOS',1);
/**
 * Description of Record_Course
 *
 * @author remote
 */
class test_unit_A25_Record_LocationTest extends test_Framework_UnitTestCase {
    /**
	 * @test
	 */
	public function nonParentIsFlaggedOnNew()
	{
		$nonParent = new A25_Record_Location();
		$this->assertEquals(1,$nonParent->is_location);
	}

	/**
	 * @test
	 */
	public function parentIsFlaggedOnNew()
	{
		$parent = new A25_Record_LocationParent();
		$this->assertEquals(0,$parent->is_location);
	}
}
?>
