<?php

class test_unit_A25_OldCom_Student_Login_CheckPasswordTest extends
    test_Framework_UnitTestCase
{
  private $student;

  public function setUp()
  {
    parent::setUp();
    $this->student = new A25_Record_Student;
    $this->student->student_id = '123';
    $this->student->salt_prefix = 'abcDEF123';
    $this->student->password = 'password';
  }
  /**
   * @test
   */
  public function trueIfCorrectPassword()
  {
    $password = 'password';
    $this->assertEquals(true, LoginWithCheckPasswordExposed::checkPassword($this->student, $password));
  }
  /**
   * @test
   */
  public function falseIfIncorrectPassword()
  {
    $password = 'incorrect';
    $this->assertEquals(false, LoginWithCheckPasswordExposed::checkPassword($this->student, $password));
  }
  /**
   * @test
   */
  public function falseIfNoStudent()
  {
    $this->student = null;
    $password = 'password';
    $this->assertEquals(false, LoginWithCheckPasswordExposed::checkPassword($this->student, $password));
  }
}

class LoginWithCheckPasswordExposed extends A25_OldCom_Student_Login
{
  public static function checkPassword($student, $password) {
    return parent::checkPassword($student, $password);
  }
}