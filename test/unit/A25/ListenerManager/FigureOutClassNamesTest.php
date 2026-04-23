<?php
require_once(dirname(__FILE__) . '/../../../../autoload.php');

class test_unit_A25_Listeners_FigureOutClassNamesTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function namesAPhpFile()
	{
		$filenames = array('Test.php');

		$listenerMan = new unit_FigureOutClassNamesTest_ListenerManager();
		$result = $listenerMan->figureOutClassNames($filenames);
		$this->assertEquals(array('A25_Plugin_Test'),$result);
	}
	/**
	 * @test
	 */
	public function removesANonPhpFile()
	{
		$filenames = array('Test.html');

		$listenerMan = new unit_FigureOutClassNamesTest_ListenerManager();
		$result = $listenerMan->figureOutClassNames($filenames);
		$this->assertEquals(array(),$result);
	}
}

class unit_FigureOutClassNamesTest_ListenerManager extends A25_ListenerManager
{
	public function figureOutClassNames(array $names)
	{
		return parent::figureOutClassNames($names);
	}
}