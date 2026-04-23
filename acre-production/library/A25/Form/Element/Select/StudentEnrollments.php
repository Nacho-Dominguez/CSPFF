<?php

class A25_Form_Element_Select_StudentEnrollments extends A25_Form_Element_Select
{
	private $student;

	public function __construct($name, A25_Record_Student $student)
	{
		parent::__construct($name);
		$this->student = $student;

		$this->setMultiOptions($this->getStudentEnrollments());
	}

	private function getStudentEnrollments()
	{
		$studentEnrollments = array();
		foreach ($this->student->Enrollments as $enroll) {
			$location = $enroll->Location;
			$studentEnrollments[$enroll->xref_id] = $enroll->xref_id . ' - '
					. $enroll->Location->location_name;

		}
		return $studentEnrollments;
	}
}
?>
