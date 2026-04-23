<?php

class test_unit_A25_Form_Element_Select_Location_SimpleSortTest
	extends test_Framework_UnitTestCase
{
	private $form;

	public function setUp()
	{
		$this->form = new unit_A25_Form_Element_Select_Location();
	}
	/**
	 * @test
	 */
    public function returnsLocationsInAlphabeticalOrder()
	{
		$locations = array(
			$this->createLocation('c'),
			$this->createLocation('a'),
			$this->createLocation('b')
		);

		$this->expectLocationsToGetSorted($locations);
	}

	/**
	 * @test
	 */
    public function returnsLocationsInAlphabeticalOrderIgnoringCase()
	{
		$locations = array(
			$this->createLocation('C'),
			$this->createLocation('a')
		);

		$this->expectLocationsToGetSorted($locations);
	}

	private function expectLocationsToGetSorted($locations)
	{
		$this->assertAlphabetical($this->form->simpleSort($locations));
	}

	private function createLocation($name)
	{
		$location = new A25_Record_Location();
		$location->location_name = $name;
		return $location;
	}

	private function assertAlphabetical($locations)
	{
		for ($i=0; $i<count($locations)-1; $i++) {
			$this->assertLessThanOrEqual(0,
					strcasecmp($locations[$i]->location_name,
							$locations[$i+1]->location_name),
					'Failed to sort ' . $locations[$i]->location_name
					. ' before ' . $locations[$i+1]->location_name);
		}
	}
}

class unit_A25_Form_Element_Select_Location
	extends A25_Form_Element_Select_Location
{
	public function  __construct() {}
	public function simpleSort($locations)
	{
		return parent::simpleSort($locations);
	}
}
