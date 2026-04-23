<?php

class A25_Plugin_SmsMessages implements A25_ListenerI_CourseActions,
    A25_ListenerI_PhoneNumbers, A25_ListenerI_Doctrine,
    A25_ListenerI_CourseEnrollmentColumn
{ 
    // If adding this plugin to a new state, you need to add a new phone number
    // on Twilio and configure it with automated replies
  public function extraCourseActionButtons($course_id)
  {
    $link = A25_Link::withoutSef('/administrator/send-sms-message-to-course?course_id=' . $course_id);
    ADMIN_HTML_course::quickiconButton( $link, 'inbox.png', 'Text message all students' );
  }
  
  public function extraShowEnrollmentHeader()
  {
      if (A25_DI::PlatformConfig()->showSMSCourseRosterField) {
        return '<td width="5%" align="center">SMS</td>';
      }
  }
  
  public function extraShowEnrollmentColumn($student)
  {
      if (A25_DI::PlatformConfig()->showSMSCourseRosterField) {
        $smsLink = '';
        if ($student->home_sms == 1 || $student->work_sms == 1) {
            $smsLink = 'Y';
        }
        return '<td align="center">' . $smsLink . '</td>';
      }
  }
  
  public function registrationFormAfterEachPhoneNumber($name)
  {
    ?>
    <div style="margin-top: 4px; margin-bottom: 8px;">
      Receive class updates & reminders via text message?
      <a href="<?php echo A25_Link::to('/terms-of-service'); ?>" target="_blank">Terms of Service</a>
      <p>
      <input type="radio" <?php if ($name == 'home') { ?>tmt:required="true" <?php }?>tmt:message="Please choose whether or not to receive text messages." name="<?php echo $name?>_sms" value="1"/> Yes <input type="radio" <?php if ($name == 'home') { ?>tmt:required="true" <?php }?>tmt:message="Please choose whether or not to receive text messages." name="<?php echo $name?>_sms" value="0"/> No
      </p>
    </div>
    <?php
  }
  
  public function registrationFormAfterEachPhone(A25_Form_Record_Register $form, $name)
  {
    $sms = new A25_Form_Element_Radio($name);
    $sms->setLabel('Receive class updates & reminders via text message?')
        ->addMultiOptions(array(1 => 'Yes', 0 => 'No'));
    $form->addElement($sms);
  }
  
  public function studentFormAfterHomePhone(A25_Form_Record_Student $form)
  {
		$sms = new A25_Form_Element_Checkbox('home_sms');
		$sms->setRequired(false)
				->setLabel('Primary Phone SMS');
		$form->addElement($sms);
  }
  
  public function studentFormAfterWorkPhone(A25_Form_Record_Student $form)
  {
		$sms = new A25_Form_Element_Checkbox('work_sms');
		$sms->setRequired(false)
				->setLabel('Secondary Phone SMS');
		$form->addElement($sms);
  }

	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
	{
		if (!$doctrineRecord instanceof A25_Record_Student)
			return;
		
    $doctrineRecord->hasColumn('home_sms', 'boolean', 1, array(
         'type' => 'boolean',
         'length' => 1,
         'fixed' => false,
         'primary' => false,
         'notnull' => true,
         'autoincrement' => false,
         ));
		
    $doctrineRecord->hasColumn('work_sms', 'boolean', 1, array(
         'type' => 'boolean',
         'length' => 1,
         'fixed' => false,
         'primary' => false,
         'notnull' => true,
         'autoincrement' => false,
         ));
	}
  
  public static function queryStudentsWhoCanReceiveInCourse($course_id)
  {
		return Doctrine_Query::create()
			->select('*')
			->from('A25_Record_Student s')
      ->innerJoin('s.Enrollments e')
			->innerJoin('e.Course c')
      ->where('c.course_id = ?', $course_id)
      ->andWhere(A25_Record_Enroll::active('e'))
      ->andWhere('s.home_sms = 1 OR s.work_sms = 1');
  }
}

set_include_path(
	ServerConfig::webRoot . '/plugins/SmsMessages' . PATH_SEPARATOR
	. get_include_path()
);