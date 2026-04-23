<?php
require_once(dirname(__FILE__) . '/../../../autoload.php');

class test_unit_A25_GridTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function ordersFieldsCorrectly()
	{
		$dataObjects = array(array('key1' => 'value1', 'key2' => 'value2'));
		$grid = new A25_Grid($dataObjects);
		$this->assertEquals(1,preg_match('/value1.*value2/',$grid->generate()));
	}
	/**
	 * @test
	 */
	public function alternatesRowStyles()
	{
		$dataObjects = array(
			array('key1' => 'value1', 'key2' => 'value2'),
			array('key1' => 'value3', 'key2' => 'value4')
		);
		$grid = new A25_Grid($dataObjects);
		$this->assertEquals(1,preg_match("/<tr class='row0'.*<tr class='row1'/",$grid->generate()));
	}
	/**
	 * @test
	 */
	public function displaysHeaderRow()
	{
		$dataObjects = array(array('key1' => 'value1', 'key2' => 'value2'));
		$grid = new A25_Grid($dataObjects);
		$this->assertEquals(1,preg_match('/key1.*key2/',$grid->generate()));
	}
	/**
	 * @test
	 */
	public function returnsEmptyStringIfNoData()
	{
		$dataObjects = array();
		$grid = new A25_Grid($dataObjects);
		$this->assertEquals('',$grid->generate());
	}
}
?>
