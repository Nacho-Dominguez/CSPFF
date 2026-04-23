<?php

class test_unit_A25_ColumnRecord_HashTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function hashesWithCorrectArguments()
	{
    $value = 'value';
    $hasher = $this->getMock('A25_Hasher', array('hash'));
    $hasher->expects($this->once())->method('hash')->with('predictable_salt',
        $value);
    
    A25_DI::setHasher($hasher);
    $column = new ColumnWithHashExposed(null, 'who-cares');
    $column->hash($value);
  }
  
	/**
	 * @test
	 */
	public function doesNotHashEmptyString()
	{
    $value = '';
    $hasher = $this->getMock('A25_Hasher', array('hash'));
    $hasher->expects($this->never())->method('hash');
    
    A25_DI::setHasher($hasher);
    $column = new ColumnWithHashExposed(null, 'who-cares');
    $this->assertEquals($value, $column->hash($value));
  }
  
	/**
	 * @test
	 */
	public function doesNotHashNull()
	{
    $value = null;
    $hasher = $this->getMock('A25_Hasher', array('hash'));
    $hasher->expects($this->never())->method('hash');
    
    A25_DI::setHasher($hasher);
    $column = new ColumnWithHashExposed(null, 'who-cares');
    $this->assertEquals($value, $column->hash($value));
  }
}

class ColumnWithHashExposed extends A25_RecordColumn
{
  public function hash($value) {
    return parent::hash($value);
  }
  protected function saltPrefix() {
    return 'predictable_salt';
  }  
}