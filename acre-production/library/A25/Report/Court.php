<?php

class A25_Report_Court extends A25_Report
{
	/**
	 * protected for testing
	 */
	protected $court_name;
	protected $city;
	protected $county;

	public function __construct(A25_ReportFilter $filter, $limit, $offset)
	{
		parent::__construct($filter, $limit, $offset);

		$this->court_name = $_REQUEST['f_court_name'];
		$this->city = $_REQUEST['f_city'];
		$this->county = $_REQUEST['f_county'];

	}

	protected function formatRow(A25_DoctrineRecord $court)
	{
		$male = $court->getNumberOfMaleStudents();
		$female = $court->getNumberOfFemaleStudents();
		$total = $male + $female;
		$county = '';
		if ($court->relatedIsDefined('Zip')) {
			$county = $court->Zip->county;
		}

		$expectedArray = array(
			'Court ID' => $court->court_id,
			'Court Name' => $court->court_name,
			'City' => $court->city,
			'County' => $county,
			'State' => $court->state,
			'# Reg' => $court->getNumberRegistered(),
			'# Com' => $court->getNumberCompleted(),
			'# M' => $male,
			'# F' => $female,
			'% M' => number_format(100*($male/$total),1) . '%',
			'% F' => number_format(100*($female/$total),1) . '%',
			'Revenue' => '$' . $court->getRevenue()
		);

		return $expectedArray;
	}

	/**
	 * @todotoday - make test for query
	 */
	protected function query()
	{
		$q = Doctrine_Query::create()
				->from('A25_Record_Court ct')
				->leftJoin('ct.Enrollments e')
				->leftJoin('e.Course c')
				->leftJoin('e.Student s')
				->leftJoin('e.Order o')
				->leftJoin('o.OrderItems i')
				->leftJoin('ct.Zip z')
				->where('c.course_start_date >= ?',
						date('Y-m-d',$this->filter->from) . ' 00:00:00')
				->andwhere('c.course_start_date <= ?',
						date('Y-m-d',$this->filter->to) . ' 23:59:59')
				->andwhere(A25_Record_Enroll::active('e'))
				->orderBy('ct.court_name');

		if ($this->court_name) {
			$q->andWhere('ct.court_name LIKE ?' , '%' . $this->court_name . '%');
		}

		if ($this->city) {
			$q->andWhere('ct.city LIKE ?' , '%' . $this->city . '%');
		}

		if ($this->county) {
			$q->andWhere('z.county LIKE ?' , '%' . $this->county . '%');
		}

		return $q;
	}

	protected function extraFilters()
	{
		echo $this->addFilterWithFieldName('Court Name', 'court_name');
		echo $this->addFilterWithFieldName('City', 'city');
		echo $this->addFilterWithFieldName('County', 'county');
	}

	protected function name()
	{
		return 'Court Statistics';
	}

	private function addFilterWithFieldName($labal,$fieldName)
	{
		return '<div style="float: left; font-weight: bold; text-align: left; margin-left: 1em;">'
				. $labal . ':<br />'
				. '<input type="text" name="f_' . $fieldName . '" value="' . $this->$fieldName . '" />'
				. '</div>';
	}
}
?>
