<?php

class test_unit_A25_Hasher_HashTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function returnsExpectedHash()
  {
    $hasher = new A25_Hasher();
    
    $this->assertEquals('P91q2QWVN58B9F9s5uc2BGmjhF7Ckgq',
        $hasher->hash('abcDEF123', 'password'));
  }
  
  /**
   * @test
   * @expectedException Exception
   */
  public function throwsExceptionIfSaltPrefixLessThan9Characters()
  {
    $hasher = new A25_Hasher();
    $hasher->hash('tooshort', 'password');
  }
  
  /**
   * @test
   * @expectedException Exception
   */
  public function throwsExceptionIfSaltPrefixLongerThan9Characters()
  {
    $hasher = new A25_Hasher();
    $hasher->hash('toolongofasalt', 'password');
  }
}
