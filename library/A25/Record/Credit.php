<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CreditRecord
 *
 * @author remote
 */
class A25_Record_Credit extends JosCredits
{
	/**
	 * @param integer $id
	 * @return A25_Record_Credit
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_Credit')->find($id);
    }

	function check() {
		if (trim($this->credit_type_id == '') || trim($this->credit_type_id < 1)) {
			$this->_error = "You must select a Credit/Scholarship Type.";
			return false;
		}
		if (trim($this->student_id == '') || trim($this->student_id < 1)) {
			$this->_error = "You must have a Student ID.";
			return false;
		}
		if (trim($this->credit_value == '') || trim($this->credit_value < 1)) {
			$this->_error = "You must select a dollar value for this credit.";
			return false;
		}
		return true;
	}
}
?>
