<?php

/**
 * @todo-soon: add tests for the rest of the functionality 
 */
class test_unit_A25_Form_Record_Course_CreateInstructor1FieldTest extends
		test_Framework_UnitTestCase
{
	private $expected;
	private $location;
	private $isReadOnly;
	private $course;
	
	/**
	 * @test
	 */
	public function integrationWithInstructorSelect()
	{
		$this->expected = array('0' => '--Select Instructor--', '7' => 'John Doe');
		
		$this->location = new CreateInstructorTest_LocationWithUser();
		$this->isReadOnly = false;
		$this->course = new A25_Record_Course();
		
		$form = new unit_CreateInstructorTest_A25_Form_Record_Course();
		$form->createInstructor1Field($this->location, $this->isReadOnly,
			$this->course);
		
		$created_element = $form->getElement('instructor_id');
		$this->assertEquals($this->expected, $created_element->getMultiOptions());
	}
}

class unit_CreateInstructorTest_A25_Form_Record_Course extends
		A25_Form_Record_Course
{
	public function __construct()
	{
	}
	public function createInstructor1Field($selected_location_id, $isReadOnly,
			$course)
	{
		return parent::createInstructor1Field($selected_location_id,
				$isReadOnly, $course);
	}
}

class CreateInstructorTest_LocationWithUser extends A25_Record_Location
{
	public function getUsers()
	{
		$instructor = new A25_Record_User();
		$instructor->id = 7;
		$instructor->name = "John Doe";
		return array($instructor);
	}
}