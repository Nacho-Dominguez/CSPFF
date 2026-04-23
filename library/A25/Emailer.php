<?php
/**
 * @author Thomas Albright
 */
class A25_Emailer
{
	public static function saveMessageToDb($studentId, $courseId,
			 $subject, $body)
	{
		// This shouldn't cause a page to die if it doesn't work.  Instead, we
		// just notify the developer there was a problem.
		try {
			$subject = mysql_real_escape_string($subject);
			$body = mysql_real_escape_string($body);
			$sql = "INSERT INTO #__student_messages SET
					student_id=$studentId,
					course_id=$courseId,
					subject='$subject',
					message='$body',
					created='" . date('Y-m-d H:i:s') . "'";
			$db = A25_DI::DB();
			$db->setQuery($sql);
			if (!$db->query()) {
				throw new Exception (A25_DI::DB()->_errorMsg);
			}
		} catch (Exception $ex) {
			A25_Emailer::emailThomasAnException($ex);
		}
	}
	/**
	 * Sends an HTML-formatted email to the student, and also saves the email
	 * in the database, in table #__student_messages.
	 */
	public static function emailStudent($student, $courseId,
			$subject, $body, $isHtml = 1, $alt_body = null)
	{
		self::saveMessageToDb($student->student_id, $courseId,
				$subject, $body);
		A25_DI::Factory()->StudentMailer()->send($student, $subject, $body, $isHtml,
        $alt_body);
	}
	public static function emailThomasAnException(Exception $ex)
	{
    $body_generator = new A25_ErrorEmailBody();
    $message = $body_generator->generate($ex->getMessage() . "\n\n"
        . $ex->getFile() . " Line " . $ex->getLine() . "\n\nTrace:\n"
        . $ex->getTraceAsString());

		A25_DI::Mailer()->mail('jonathan@appdevl.net',
				ServerConfig::staticHttpUrl() . ' Exception Thrown', $message, false);
	}
}
