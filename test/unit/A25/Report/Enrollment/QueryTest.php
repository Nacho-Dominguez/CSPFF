<?php
class test_unit_QueryTest_Enrollment extends A25_Report_Enrollment
{
	public function query()
	{
		return parent::query();
	}
}

/**
 * @runTestsInSeparateProcess
 */
class test_unit_A25_Report_Enrollment_QueryTest extends
		test_Framework_UnitTestCase
{
	private $report;
	private $filter;

	public function setUp()
	{
		$this->filter = new A25_ReportFilter();
		$this->filter->from = '01-01-2010';
		$this->filter->to = '01-02-2010';

		$this->report = new test_unit_QueryTest_Enrollment($this->filter,null,null);
	}
	/**
	 * @test
	 */
	public function returnsExpectedQuery()
	{
		$expected = new A25_Query();
		$expected->from('A25_Record_Enroll e')
			->innerJoin('e.Course c')
			->innerJoin('e.Student s')
			->innerJoin('e.Status status')
      ->innerJoin('e.ReasonType r')
      ->leftJoin('e.Court court')
			->orderBy('s.last_name, s.first_name');

		$this->assertEquals($expected->getDql(), $this->report->query()->getDql());
	}
}