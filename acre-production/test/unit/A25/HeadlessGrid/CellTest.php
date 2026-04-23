<?php

class test_unit_A25_HeadlessGrid_CellTest extends test_Framework_UnitTestCase {
    /**
	 * @test
	 */
	public function simpleCase()
	{
		$grid = new CellTest_Exposed_HeadlessGrid(array());
		$this->assertEquals('<td>value</td>', $grid->cell('value'));
    }
    /**
	 * @test
	 */
	public function cssKeyDoesNotExist()
	{
		$grid = new CellTest_Exposed_HeadlessGrid();
		$this->assertEquals('<td>value</td>', $grid->cell('value', 'Not Set'));
    }
    /**
	 * @test
	 */
	public function insertsCssStyling()
	{
		$grid = new CellTest_Exposed_HeadlessGrid();
		$grid->setColumnCss('Zip', 'text-align: right;');
		$this->assertEquals('<td style="text-align: right;">value</td>',
				$grid->cell('value', 'Zip'));
    }
}

class CellTest_Exposed_HeadlessGrid extends A25_HeadlessGrid
{
	public function __construct()
	{

	}
	public function cell($value, $key = null)
	{
		return parent::cell($value, $key);
	}
}