<?php
require_once(dirname(__FILE__) . '/../../../../autoload.php');
require_once(dirname(__FILE__) . '/../../../../includes/database.php');

define('_VALID_MOS', 1);

class test_unit_A25_Record_StudentTest extends test_Framework_UnitTestCase
{
	private $_studentRecord;
	function setUp()
	{
		parent::setUp();
		$db = $this->mock('A25_Db');
		A25_DI::setDB($db);
		$this->_studentRecord = new A25_Record_Student();
	}
	function test_checkDOB_returnsTrueWhenValid()
	{
		$age = new A25_Age(PlatformConfig::maxAge,364);
		$this->_studentRecord->date_of_birth = $age->formattedBirthday();
		$this->assertTrue($this->_studentRecord->checkAgeAtTimestamp(time()));
	}
	/**
	 * Should compare age with class date.
	 */
	function test_checkDOB_returnsErrorMessageWhenTooOld()
	{
		$age = new A25_Age(PlatformConfig::maxAge+1);
		$this->_studentRecord->date_of_birth = $age->formattedBirthday();
		try{
			$this->_studentRecord->checkAgeAtTimestamp(time());
		}catch(A25_Exception_DataConstraint $e){
			$this->assertEquals(
					'You must be younger than ' . (PlatformConfig::maxAge+1)
					. ' on the course date to enroll.', $e->getMessage());
		}
	}
	function test_checkDOB_returnsErrorMessageWhenTooYoung()
	{
		$age = new A25_Age(PlatformConfig::minAge-1);
		$this->_studentRecord->date_of_birth = $age->formattedBirthday();
		try{
			$this->_studentRecord->checkAgeAtTimestamp(time());
		}catch(A25_Exception_DataConstraint $e){
			$this->assertEquals('You must be ' . PlatformConfig::minAge
				. ' or older on the course date to enroll.', $e->getMessage());
		}
	}
	public function test_isUserIdAvailable_returnsTrue()
	{
		$db = $this->mock('A25_Db');
		A25_DI::setDB($db);
		$this->assertTrue(A25_Record_Student::isUserIdAvailable('nonTakenId'));
	}
}
?>
