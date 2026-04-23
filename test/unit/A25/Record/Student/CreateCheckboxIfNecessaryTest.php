<?php

class test_unit_A25_Record_Student_CreateCheckboxIfNecessaryTest extends
    test_Framework_UnitTestCase
{
  private $student;
  private $existing_checkbox;

  public function setUp() {
    parent::setUp();
    $this->student = new A25_Record_Student();
    $this->existing_checkbox = new Checkbox();
    $this->existing_checkbox->text = "This checkbox exists";
    $this->existing_checkbox->Student = $this->student;
  }
  /**
   * @test
   */
  public function createsCheckboxIfNotExisting()
  {
    $text = "This checkbox doesn't exist";
    $this->student->createCheckboxIfNecessary($text);
    $this->assertEquals(2, $this->student->Checkboxes->count());
  }
  /**
   * @test
   */
  public function doesNotCreateCheckboxIfExists()
  {
    $text = "This checkbox exists";
    $this->student->createCheckboxIfNecessary($text);
    $this->assertEquals(1, $this->student->Checkboxes->count());
  }
}
