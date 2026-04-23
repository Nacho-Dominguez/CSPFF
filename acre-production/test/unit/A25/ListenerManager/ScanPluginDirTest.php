<?php

class test_unit_A25_Listeners_ScanPluginDirTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function listenerIsSetupCorrectly()
	{
		$listenerManager = new unit_ScanPluginDirTest_ListenerManager();
		$listenerManager->scanPluginDir();
		
		$this->assertTrue($listenerManager->listeners[0] instanceof A25_Plugin_Test);
		$this->assertTrue($listenerManager->listeners[1] instanceof A25_Plugin_Other);
	}
}

class unit_ScanPluginDirTest_ListenerManager extends A25_ListenerManager
{
	public $listeners;
	public function scanDir($path)
	{
		return array('Test.php','Other.php');
	}
}

class A25_Plugin_Test
{
}
class A25_Plugin_Other
{
}