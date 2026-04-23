<?php
class A25_Plugin_SpanishZoom implements
    A25_ListenerI_Doctrine,
    A25_ListenerI_RegisterFormOtherInformation,
    A25_ListenerI_SendAdditionalEmailAfterPayment
{
    public function afterSpecialNeeds() {
        echo '
		<tr>
			<td class="formlabel"><label for="spanish">Check here if you prefer a spanish-language book (Marque aqu&iacute; si Marca aquí si prefieres un libro en espa&ntilde;ol):</label></td>
			<td><input type="checkbox" name="spanish" id="spanish" value="" /></td>
		</tr>';
    }
    
    public function afterSpecialNeedsAdmin(A25_Form_Record_Student $form) {
        $spanish = new A25_Form_Element_Checkbox('spanish');
        $spanish->setLabel('Check here if you prefer a spanish-language book');
        $form->addElement($spanish);
    }

    public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
    {
        if (!$doctrineRecord instanceof A25_Record_Student) {
            return;
        }

        $doctrineRecord->hasColumn('spanish', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             ));
    }
    
	public function sendAdditionalEmailAfterPayment(A25_Record_Enroll $enroll)
  {
    if (!$enroll->course_id)
      return;
		if($enroll->courseIsPast())
			return;

		$student = $enroll->Student;
		if ($student->spanish) {
			$course = $enroll->Course;
			$subject = A25_EmailContent::wrapSubject('Notification of spanish-language student');
			$body = "A student in your class has requested a spanish-language book.\n\n" .
					"First name: $student->first_name\n" .
					"Last name: $student->last_name\n" .
					"Course Date: " . $course->prettyDateTime() . "\n" .
					"Course Location: " . $course->getLocationName();

			A25_DI::Mailer()->mail(ServerConfig::adminEmailAddress,
					$subject, $body, false);
			if ($course->relatedIsDefined('Instructor')) {
				$instructor = $course->Instructor;
				A25_DI::Mailer()->mail($instructor->email, $subject, $body, false);
			}
		}
	}
}
