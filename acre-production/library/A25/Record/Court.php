<?php

class A25_Record_Court extends JosCourt implements A25_ISelectable
{
	/**
	 * @param integer $id
	 * @return A25_Record_Court
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_Court')->find($id);
	}
	function check() {
		// check for valid court name
		if (trim($this->court_name == '')) {
			$this->_error = "Court name cannot be empty.";
			return false;
		}

		// check for valid state name
		if (trim($this->state == '')) {
			$this->_error = "Court state cannot be empty.";
			return false;
		}

		return true;
	}
	public function getSelectionName() {
		return $this->court_name;
	}
	public function getSurchargeFee()
	{
		if(!is_null($this->surcharge_fee))
		{
			return $this->surcharge_fee;
		}
		return PlatformConfig::defaultCourtSurcharge;
	}
	public function getNumberRegistered()
	{
		$total = 0;
		foreach ($this->Enrollments as $enroll) {
			if (in_array($enroll->status_id, A25_Record_Enroll::occupiesSeatStatusList())) {
				$total++;
			}
		}
		return $total;
	}
	public function getNumberCompleted()
	{
		$total = 0;
		foreach ($this->Enrollments as $enroll) {
			if ($enroll->status_id == A25_Record_Enroll::statusId_completed) {
				$total++;
			}
		}
		return $total;
	}
	public function getNumberOfMaleStudents()
	{
		return $this->getNumberOfStudentsWithGender('M');
	}
	public function getNumberOfFemaleStudents()
	{
		return $this->getNumberOfStudentsWithGender('F');
	}
	/**
	 * Protected for testing
	 * 
	 * @param string $gender
	 * @return int $total
	 */
	protected function getNumberOfStudentsWithGender($gender)
	{
		$total = 0;
		foreach ($this->Enrollments as $enroll) {
			if ($enroll->status_id != A25_Record_Enroll::statusId_canceled
          && $enroll->status_id != A25_Record_Enroll::statusId_kickedOut &&
					$enroll->Student->gender == $gender) {
				$total++;
			}
		}
		return $total;
	}
	public function getRevenue()
	{
		$total = 0;
		foreach ($this->Enrollments as $enroll) {
      $items = $enroll->lineItems();
      if (empty($items))
        continue;
			foreach ($items as $item) {
				if ($item->isPaid() && $item->type_id
						!= A25_Record_OrderItemType::typeId_CourtSurcharge) {
					$total += $item->chargeAmount();
				}
			}
		}
		return $total;
	}
}
