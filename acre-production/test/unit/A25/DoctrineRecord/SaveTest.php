<?php

class test_unit_A25_DoctrineRecord_SaveTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 * @expectedException Exception
	 */
	public function throwsExceptionIfSaveDisabled()
	{
		A25_DoctrineRecord::$disableSave = true;

		$student = new A25_Record_Student();
		$student->save();
	}
}