<?php

class test_unit_A25_Record_Student_ZipMutatorTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function setsPasswordToHashedZipIfZipIsNew()
	{
    A25_DI::setHasher(new HasherWithPredictableSalt());
    $salt = A25_DI::Hasher()->generateSalt();
    
    $zip = '80401';
    $expected_hash = A25_DI::Hasher()->hash($salt, $zip);

    $student = new A25_Record_Student();
    $student->zip = $zip;
    
    $this->assertEquals($expected_hash, $student->password);
  }
  
	/**
	 * @test
	 */
	public function setsPasswordToHashedZipIfZipChanges()
	{
    A25_DI::setHasher(new HasherWithPredictableSalt());
    $salt = A25_DI::Hasher()->generateSalt();
    
    $zip = '80401';
    $expected_hash = A25_DI::Hasher()->hash($salt, $zip);

    $student = new A25_Record_Student();
    $student->zip = 'old_zip';
    $student->zip = $zip;
    
    $this->assertEquals($expected_hash, $student->password);
  }
  
	/**
	 * @test
	 */
	public function doesNothingIfZipChangesToEmptyString()
	{
    A25_DI::setHasher(new HasherWithPredictableSalt());
    $salt = A25_DI::Hasher()->generateSalt();
    
    $zip = '80401';
    $expected_hash = A25_DI::Hasher()->hash($salt, $zip);

    $student = new A25_Record_Student();
    $student->zip = $zip;
    $student->zip = "";
    
    $this->assertEquals($expected_hash, $student->password);
  }
  
	/**
	 * @test
	 */
	public function doesNothingIfZipChangesToNull()
	{
    A25_DI::setHasher(new HasherWithPredictableSalt());
    $salt = A25_DI::Hasher()->generateSalt();
    
    $zip = '80401';
    $expected_hash = A25_DI::Hasher()->hash($salt, $zip);

    $student = new A25_Record_Student();
    $student->zip = $zip;
    $student->zip = null;
    
    $this->assertEquals($expected_hash, $student->password);
  }
}

class HasherWithPredictableSalt extends A25_Hasher
{
  public function generateSalt() {
    return 'predicted';
  }
}