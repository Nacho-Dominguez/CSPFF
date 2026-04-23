<?php

class A25_Record_Note extends A25_Record {
	/** @var int(11)*/
	var $note_id = null;
	/** @var int(11)*/
	var $student_id = null;
	/** @var int(11)*/
	var $xref_id = null;
	/** @var text*/
	var $note = null;
	/** @var datetime */
	var $created = null;
	/** @var int User id*/
	var $created_by = null;

	/**
	 * Instantiates the student note class
	 * @author Christiaan van Woudenberg
	 * @version July 28, 2006
	 *
	 * @return boolean
	 */
	function __construct( &$db ) {
		$this->mosDBTable( '#__student_note', 'note_id', $db );
	}

	/**
	 * Checks the student object for data consistency
	 * @author Christiaan van Woudenberg
	 * @version June 20, 2006
	 *
	 * @return boolean
	 */
	function check() {
		// check for valid note entry
		if (trim($this->note == '')) {
			$this->_error = "Note cannot be empty.";
			return false;
		}
		if ((int) $this->student_id == 0) {
			$this->_error = "Student id cannot be empty.";
			return false;
		}
		return true;
	}
}
