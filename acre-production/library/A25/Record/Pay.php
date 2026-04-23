<?php
/**
 * Pay class.  It is important to note that the relationship with student is the
 * most important.  As far as the program account balance logic goes, it does
 * not matter (much) what order or enrollment a payment was for.
 *
 * @author Christiaan van Woudenberg
 * @version August 3, 2006
 *
 * @return void
 */
class A25_Record_Pay extends JosPay {
	private $paymentTypeRecord;

	const typeId_Cash = 1;
	const typeId_Check = 2;
	const typeId_CreditCard = 3;
	const typeId_MoneyOrder = 4;
	const typeId_ScholarshipCredit = 5;

	/**
	 * @param integer $id
	 * @return A25_Record_Pay
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_Pay')->find($id);
    }

	function check() {
		if ($this->amount == 0) {
			$this->_error = "Amount may not be empty for payment.";
			return false;
		}
		if ($this->student_id < 1) {
			$this->_error = "Payment must be associated with a student.";
			return false;
        }
		if ($this->pay_type_id < 1) {
			$this->_error = "Payment must have a Payment Type.";
			return false;
		}
		if ($this->order_id < 1) {
			$this->_error = "Payment must have an Order Id.";
			return false;
		}
		if ($this->xref_id < 1) {
			$this->_error = "Payment must have an Xref Id.";
			return false;
		}
	    return true;
	}
	public function getPaymentTypeName()
	{
		return $this->getPaymentType()->pay_type_name;
    }

	private function getPaymentType()
	{
		if (!$this->paymentTypeRecord)
			$this->paymentTypeRecord =
				A25_Record_PaymentType::retrieve($this->pay_type_id);
		return $this->paymentTypeRecord;
    }

	public function assignOrder($orderRecord)
	{
		$this->Order = $orderRecord;
		$this->Enrollment = $orderRecord->Enrollment;
		$this->Student = $orderRecord->Enrollment->Student;
    }
	public function assignEnrollment(A25_Record_Enroll $enroll)
	{
		$this->Enrollment = $enroll;
		$this->Student = $enroll->Student;
		$this->Order = $enroll->Order;
	}
	public function getAmount()
	{
		return $this->amount;
	}
	public function associateWithScholarship($credit_type_id)
	{
		$credit = new A25_Record_Credit();
		$credit->credit_type_id = $credit_type_id;
		$credit->pay_id = $this->pay_id;
		$credit->student_id = $this->student_id;
		$credit->xref_id = $this->xref_id;
		$credit->credit_value = $this->amount;
		$credit->checkAndStore();

		return $credit;
	}

  public function getOrder()
  {
    if ($this->relatedIsDefined('Enrollment'))
      return $this->Enrollment->Order;
  }

  public function getStudent()
  {
      return $this->Student;
  }

  public function getEnrollment()
  {
    if ($this->relatedIsDefined('Order'))
      return $this->Order->Enrollment;
  }

  public function getCourse()
  {
    if ($this->relatedIsDefined('Enrollment'))
      return $this->Enrollment->Course;
  }
}
?>
