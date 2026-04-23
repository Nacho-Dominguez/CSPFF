<?php

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Instructor class
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @return void
 */
class mosInstructor extends mosDBTable {
	/** @var int */
	var $user_id = null;
	/** @var varchar(255) */
	var $address_1 = null;
	/** @var varchar(255) */
	var $address_2 = null;
	/** @var varchar(80) */
	var $city = null;
	/** @var char(2) */
	var $state = null;
	/** @var varchar(10) */
	var $zip = null;
	/** @var varchar(30) */
	var $home_phone = null;
	/** @var varchar(30) */
	var $work_phone = null;
	/** @var varchar(50) */
	var $work_ext = null;
	/** @var int(11) */
	var $nsc = null;
	/** @var int(11) */
	var $control = null;
	/** @var float */
	var $single_fee = null;
	/** @var float */
	var $multiple_fee = null;
	/** @var datetime */
	var $created = null;
	/** @var int User id*/
	var $created_by = null;
	/** @var datetime */
	var $modified = null;
	/** @var int User id*/
	var $modified_by = null;
	/** @var int unsigned */
	var $checked_out = null;
	/** @var datetime */
	var $checked_out_time = null;

	/**
	 * Instantiates the instructor class
	 * @author Christiaan van Woudenberg
	 * @version June 20, 2006
	 *
	 * @return boolean
	 */
	function mosInstructor( &$db ) {
		$this->mosDBTable( '#__instructor', 'user_id', $db );
	}
	function check() {
		// check for valid instructor name
		if (trim($this->user_id == '')) {
			$this->_error = "Instructor must be linked to a user record.";
			return false;
		}

		// check for valid state name
		if (trim($this->state == '')) {
			$this->_error = "Instructor state cannot be empty.";
			return false;
		}

		return true;
	}
}
?>
