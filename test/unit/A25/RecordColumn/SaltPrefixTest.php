<?php

class test_unit_A25_ColumnRecord_SaltPrefixTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function whenAlreadySaltPrefix_usesIt()
	{
    A25_DI::setHasher(new PredicatableSaltingHasher());
    
    $record = new A25_Record_Student();
    $record->salt_prefix = 'old_salt';
    
    $column = new ColumnWithSaltPrefixExposed($record, 'who_cares');
    
    $this->assertEquals('old_salt', $column->saltPrefix());
	}
  
	/**
	 * @test
	 */
	public function whenSaltPrefixIsNull_generatesSaltAndSetsField()
	{ 
    A25_DI::setHasher(new PredicatableSaltingHasher());
    
    $record = new A25_Record_Student();
    $record->salt_prefix = null;
    
    $column = new ColumnWithSaltPrefixExposed($record, 'who_cares');
    
    $this->assertEquals('a_predictable_salt', $column->saltPrefix());
	}
  
	/**
	 * @test
	 */
	public function whenSaltPrefixIsEmpty_generatesSaltAndSetsField()
	{
    A25_DI::setHasher(new PredicatableSaltingHasher());
    
    $record = new A25_Record_Student();
    $record->salt_prefix = '';
    
    $column = new ColumnWithSaltPrefixExposed($record, 'who_cares');
    
    $this->assertEquals('a_predictable_salt', $column->saltPrefix());
	}
}

class PredicatableSaltingHasher extends A25_Hasher
{
  /**
   * @override
   */
  public function generateSalt() {
    return 'a_predictable_salt';
  }
}

class ColumnWithSaltPrefixExposed extends A25_RecordColumn
{
  public function saltPrefix() {
    return parent::saltPrefix();
  }
}