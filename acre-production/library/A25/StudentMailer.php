<?php

class A25_StudentMailer
{
	public function send(A25_Record_Student $student, $subject, $body,
      $isHtml = 1, $alt_body = null)
	{
		if ($student->email) {
            $first_name = preg_replace("/[^A-Za-z]/", '', $student->first_name);
            $last_name = preg_replace("/[^A-Za-z]/", '', $student->last_name);
			$recipient = "\"$first_name $last_name\" "
					   . "<$student->email>";
			A25_DI::Mailer()->mail($recipient, $subject, $body, $isHtml, $alt_body);
		}
	}
}
