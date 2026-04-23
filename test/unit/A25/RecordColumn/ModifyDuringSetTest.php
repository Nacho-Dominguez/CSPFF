<?php

class test_unit_A25_ColumnRecord_ModifyDuringSetTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function callsHashIfHashed()
	{
    $column = new HashedRecordColumn(null, null);
    $this->assertEquals('hashed', $column->modifyDuringSet('not hashed'));
	}
	/**
	 * @test
	 */
	public function doesNotCallHashIfHashed()
	{
    $column = new NonHashedRecordColumn(null, null);
    $this->assertEquals('not hashed', $column->modifyDuringSet('not hashed'));
	}
}

class ModifyDuringSetTest_RecordColumn extends A25_RecordColumn
{
  /**
   * @override
   */
  protected function hash()
  {
    return 'hashed';
  }
}

class HashedRecordColumn extends ModifyDuringSetTest_RecordColumn
{
  /**
   * @override
   */
  public function isHashed() {
    return true;
  }
}

class NonHashedRecordColumn extends ModifyDuringSetTest_RecordColumn
{
  /**
   * @override
   */
  public function isHashed() {
    return false;
  }
}
