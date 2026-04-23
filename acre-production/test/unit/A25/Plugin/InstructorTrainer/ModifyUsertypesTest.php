<?php

require_once ServerConfig::webRoot . '/plugins/InstructorTrainer.php';
require_once ServerConfig::webRoot
		. '/administrator/components/com_messages/admin.messages.html.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Plugin_InstructorTrainer_ModifyUsertypesTest extends
		test_Framework_UnitTestCase
{
	public function setUp()
	{
		parent::setUp();
		
		set_include_path($this->original_include_path . PATH_SEPARATOR
				. ServerConfig::webRoot . '/plugins/InstructorTrainer');
	}

	/**
	 * @test
	 */
	public function insertsTrainerAfterInstructor()
	{
		$listener = new A25_Plugin_InstructorTrainer();
		
		$expected = array('Instructor', 'Instructor Trainer',
				'Super Administrator');
		$result = $listener->modifyUsertypes(array('Instructor',
				'Super Administrator'));
		
		$this->assertEquals($expected, $result);
	}
}
