<?php

class test_unit_A25_CourseRunner_CreateEnrollmentTest extends
		test_Framework_UnitTestCase
{
	private $runner;
	
	public function setUp()
	{
		parent::setUp();
		$this->runner = $this->getMock(
				'unit_createEnrollmentTest_A25_CourseRunner',
				array('validatePost', 'makeTheEnrollment'));
	}
	/**
	 * @test
	 */
	public function callsMakeTheEnrollment()
	{
		$this->runner->expects($this->once())->method('makeTheEnrollment');
		$this->runner->createEnrollment();
	}
	/**
	 * @test
	 */
	public function returnsEnrollment()
	{
		$this->assertEquals($this->runner->getEnroll(),
				$this->runner->createEnrollment());
	}

}

class unit_createEnrollmentTest_A25_CourseRunner extends
		A25_CourseRunner
{
	public function __construct()
	{
		$this->_enroll = 89;
	}
	public function getEnroll()
	{
		return $this->_enroll;
	}
}
