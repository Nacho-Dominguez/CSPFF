<?php

/**
 * Student message class.  Used by administrative sides of com_course and 
 * com_student to send messages to students from the administrator panel.
 *
 * @package aliveat25_components
 * @subpackage student
 * @author Christiaan van Woudenberg
 * @version August 8, 2006
 *
 * @return void
 */
class A25_Record_StudentMessage extends JosStudentMessages
{
	public $_msg;

	/**
	 * @param integer $id
	 * @return A25_Record_StudentMessage
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_StudentMessage')->find($id);
	}

	/**
	* Validation and filtering
	*/
	function check() {
		return true;
	}

	/**
	* Add course information
	*/
	function addCourseInfo() {
		if (!$this->course_id) {
			return false;
		}
		$course = A25_Record_Course::retrieve( $this->course_id );
		$this->message .= "\n\n-----------------------------\n" . $course->showCourseInfo('email');
		return true;
	}

	/**
	* Send message and store into db.
	*/
	function send() {
		global $my;

		$re = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';

		$user = new A25_Record_User();
		$user->load ( $my->id );
        
        $attachment = null;
        $tmpLocation = '/tmp/' . $_FILES["attachment"]["name"];
        if(!empty($_FILES["attachment"]["name"])){
            move_uploaded_file($_FILES["attachment"]["tmp_name"], $tmpLocation);
            $attachment = $tmpLocation;
        }

		if ($this->save()) {
			$this->addCourseInfo();
			$sql = "SELECT s.`first_name`,s.`last_name`,s.`email` AS `email_address`,CONCAT('\"',s.`first_name`,' ',s.`last_name`,'\" <',s.`email`,'>') AS `email`"
				. "\n FROM `#__student` s"
				. "\n WHERE s.`student_id`='" . (int) $this->student_id . "'"
				;
			A25_DI::DB()->setQuery($sql);
			$row = null;
			A25_DI::DB()->loadObject( $row );

			if (preg_match($re,$row->email_address)) {
				$to = $row->email;
                if (A25_DI::PlatformConfig()->copyStudentEmailToSender) {
                    mosMail(A25_DI::PlatformConfig()->sendFromEmail, $user->name, $to, $this->subject,
                            $this->message, 0, null, array('aliveat25copies@gmail.com', $user->email), $attachment, $user->email);
                }
                else {
                    mosMail(A25_DI::PlatformConfig()->sendFromEmail, $user->name, $to, $this->subject,
                            $this->message, 0, null, 'aliveat25copies@gmail.com', $attachment, $user->email);
                }
				$this->_msg = "Student message added to account for " . $row->first_name . ' ' . $row->last_name . '. Copy sent via e-mail.';
			} else {
				$this->_msg = "Student message added to account for " . $row->first_name . ' ' . $row->last_name . '. The message was not emailed because the email address is invalid ('.$row->email_address.')';
			}
			return true;
		}
		return false;
	}
}
?>
