<?php
require_once(dirname(__FILE__) . '/../../autoload.php');
class test_live_FrontendTest extends
		test_Framework_SeleniumTestCase
{
        public function setUp()
        {
                parent::setUp();
                A25_DoctrineRecord::$disableSave = true;
        }
	function testABunchOfAreas()
	{
		$atBottomOfEveryPage = "Colorado State Patrol Family Foundation";
		$this->open(PlatformConfigState::findACourseUrl());
		$this->assertTextPresent($atBottomOfEveryPage);
		$this->open(PlatformConfigState::accountUrl());
		$this->assertTextPresent($atBottomOfEveryPage);
		$this->open(PlatformConfigState::contactUrl());
		$this->assertTextPresent($atBottomOfEveryPage);
	}
}
