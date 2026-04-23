<?php

/**
 * A25_DoctrineRecord->_set() [1 underscore, not 2] runs after Doctrine mutators
 * but before actual data update. It is basically the last chance to modify the
 * field value.
 */
class test_unit_A25_DoctrineRecord_UnderscoreSetTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function callsModifyDuringSet()
	{
    A25_DI::setHasher(new PredicatableSaltingAndHashingHasher());
    
    $student = new StudentWithSetExposed();
    $student->_set('password', 'not_hashed');
    
    $this->assertEquals(
        'a_predictable_hash',
        $student->password);
	}
}

class StudentWithSetExposed extends A25_Record_Student
{
  public function _set($fieldName, $value, $load = true) {
    return parent::_set($fieldName, $value, $load);
  }
}

class PredicatableSaltingAndHashingHasher extends A25_Hasher
{
  /**
   * @override
   */
  public function generateSalt() {
    return 'a_predictable_salt';
  }
  
  /**
   * @override
   */
  public function hash($salt_prefix, $password) {
    return 'a_predictable_hash';
  }
}