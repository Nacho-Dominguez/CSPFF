<?php
class test_unit_A25_FormLoaderTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function parseRecordClassName_MatchesWithoutUnderscores()
	{
		$className = A25_FormLoader::parseRecordClassName('Student');
		$this->assertEquals('A25_Record_Student', $className);
	}
	/**
	 * @test
	 */
	public function parseRecordClassName_MatchesWithUnderscores()
	{
		$className = A25_FormLoader::parseRecordClassName('Student_Admin');
		$this->assertEquals('A25_Record_Student', $className);
	}
}
?>
