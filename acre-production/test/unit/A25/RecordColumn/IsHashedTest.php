<?php

class test_unit_A25_ColumnRecord_IsHashedTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function returnsFalseIfHashedNotMentioned()
	{
    $record = new IsHashedTest_DoctrineRecord();
    $record->hasColumn('password', 'string', 31, array(
      'type' => 'string',
      'length' => 31,
     ));
    $column = new RecordColumnWithIsHashedExposed($record, 'password');
    $this->assertFalse($column->isHashed());
	}
  
	/**
	 * @test
	 */
	public function returnsFalseIfHashedMarkedFalse()
	{
    $record = new IsHashedTest_DoctrineRecord();
    $record->hasColumn('password', 'string', 31, array(
      'type' => 'string',
      'length' => 31,
      'hashed' => false
     ));
    $column = new RecordColumnWithIsHashedExposed($record, 'password');
    $this->assertFalse($column->isHashed());
	}
  
	/**
	 * @test
	 */
	public function returnsTrueIfHashedMarkedTrue()
	{
    $record = new IsHashedTest_DoctrineRecord();
    $record->hasColumn('password', 'string', 31, array(
      'type' => 'string',
      'length' => 31,
      'hashed' => true
     ));
    $column = new RecordColumnWithIsHashedExposed($record, 'password');
    $this->assertTrue($column->isHashed());
	}
  
	/**
	 * @test
	 */
	public function returnsFalseIfColumnDoesNotExist()
	{
    $record = new IsHashedTest_DoctrineRecord();
    $column = new RecordColumnWithIsHashedExposed($record, 'non-existant');
    $this->assertFalse($column->isHashed());
	}
  
	/**
	 * @test
	 */
	public function returnsFalseIfFieldIsARelationshipInsteadOfAColumn()
	{
    $record = new A25_Record_Course();
    $column = new RecordColumnWithIsHashedExposed($record, 'Location');
    $this->assertFalse($column->isHashed());
	}
}

class IsHashedTest_DoctrineRecord extends A25_DoctrineRecord
{
  public function setTableDefinition()
  {
    $this->setTableName('fake_table');
  }
}

class RecordColumnWithIsHashedExposed extends A25_RecordColumn {
  public function isHashed() {
    return parent::isHashed();
  }
}