<?php

class A25_MethodObject_GenerateReasonList extends
		A25_Default_MethodObject_GenerateReasonList
{
	protected function reasonListQuery()
	{
		$q = parent::reasonListQuery();

		return A25_DrivingPermitDiscount::appendReasonListQuery($q, $this->_student,
				$this->_course);
	}
}
