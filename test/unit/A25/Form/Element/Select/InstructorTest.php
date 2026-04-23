<?php

class test_unit_A25_Form_Element_Select_InstructorTest extends
		test_Framework_UnitTestCase
{
	private $expected;
	private $location;
	private $isReadOnly;
	
	/**
	 * @test
	 */
	public function withNoLocationSelected()
	{
		$this->expected = array('--Available After Selecting Location--');
		
		$this->location = null;
		$this->isReadOnly = false;
		
		$this->checkMultiOptions();
	}
	
	private function checkMultiOptions()
	{
		$element = new A25_Form_Element_Select_Instructor('instructor_id',
				$this->location, $this->isReadOnly);
    
    if ($this->isReadOnly)
      $element->setReadOnly();
		
		$this->assertEquals($this->expected, $element->getMultiOptions());
	}
	
	/**
	 * @test
	 */
	public function withInstructorlessLocationSelected()
	{
		$this->expected = array('--Select Instructor--');
		
		$this->location = new UserlessLocation();
		$this->isReadOnly = false;
		
		$this->checkMultiOptions();
	}
	
	/**
	 * @test
	 */
	public function withLocationSelected()
	{
		$this->expected = array('0' => '--Select Instructor--',
				'7' => 'John Doe');

		$this->location = new LocationWithUser();
		$this->isReadOnly = false;
		
		$this->checkMultiOptions();
	}
	
	/**
	 * @test
	 */
	public function withReadOnlyAndNoInstructors()
	{
		$this->expected = array();
		
		$this->location = new UserlessLocation();
		$this->isReadOnly = true;
		
		$this->checkMultiOptions();
	}
	
	/**
	 * @test
	 */
	public function withReadOnlyAndInstructors()
	{
		$this->expected = array('7' => 'John Doe');

		$this->location = new LocationWithUser();
		$this->isReadOnly = true;
		
		$this->checkMultiOptions();
	}
}

class UserlessLocation extends A25_Record_Location
{
	public function getUsers()
	{
		return null;
	}
}

class LocationWithUser extends A25_Record_Location
{
	public function getUsers()
	{
		$instructor = new A25_Record_User();
		$instructor->id = 7;
		$instructor->name = "John Doe";
		return array($instructor);
	}
}
