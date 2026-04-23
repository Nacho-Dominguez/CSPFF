<?php

abstract class test_unit_A25_Filter_ModifyQueryTestTemplate extends
		test_Framework_UnitTestCase
{
	abstract protected function className();
	
	/**
	 * @test
	 */
	public function leavesQueryAloneWhenValueIsUndefined()
	{
		$q = A25_Query::create()->from('A25_Record_Course c');
		
		$expectedDql = $q->getDql();
		
		$className = $this->className();
		$filter = new $className();
			
		$q = $filter->modifyQuery($q);
		
		$this->assertEquals($expectedDql, $q->getDql());
	}
	
}
