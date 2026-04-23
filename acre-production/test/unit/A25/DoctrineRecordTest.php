<?php
require_once(dirname(__FILE__) . '/../../../autoload.php');

class test_unit_A25_DoctrineRecordTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function addCreationInfo_createdDateIsNull()
	{
		$student = new A25_Record_Student();
		$student->created = null;
		$student->addCreationInfo();
		$this->assertEquals(date( 'Y-m-d H:i:s' ), $student->created);
	}
	/**
	 * @test
	 */
	public function addCreationInfo_createdDateIsZero()
	{
		$student = new A25_Record_Student();
		$student->created = '0000-00-00';
		$student->addCreationInfo();
		$this->assertEquals(date( 'Y-m-d H:i:s' ), $student->created);
	}

	/**
	 * @test
	 */
	public function addCreationInfo_createdBy()
	{
		$user_id = '1';
		A25_DI::setUserId($user_id);
		$student = new A25_Record_Student();
		$student->addCreationInfo();
		$this->assertEquals($user_id, $student->created_by);
	}

	/**
	 * @test
	 */
	public function addCreationInfo_createdByAlreadyExists()
	{
		$user_id = '1';
		$another_user_id='2';
		A25_DI::setUserId($another_user_id);
		$student = new A25_Record_Student();
		$student->created_by = $user_id;
		$student->addCreationInfo();
		$this->assertEquals($user_id, $student->created_by);
	}

	/**
	 * @test
	 */
	public function addCreationInfo_createdDateAlreadyExists()
	{
		$pastDate = date('Y-m-d H:i:s',strtotime('-2 days'));
		$student = new A25_Record_Student();
		$student->created = $pastDate;
		$student->addCreationInfo();
		$this->assertEquals($pastDate, $student->created);
	}
}
?>
