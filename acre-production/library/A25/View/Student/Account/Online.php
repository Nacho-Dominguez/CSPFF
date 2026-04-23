<?php

class A25_View_Student_Account_Online extends A25_View_Student_Account
{
  // These constants are equal to the course id's
  const DDC_ENGLISH = 1;
  const DDC_MOTORCYCLE = 2;
  const DDC_SPANISH = 3;
  const DISTRACTED_DRIVING = 4;
  const AFO_MOD = 5;
  const AGG_MOD = 6;
  const BPLS_MOD = 7;
  const FATI_MOD = 8;
  const IMPA_MOD = 9;
  const INTE_MOD = 10;
  const LANE_MOD = 11;
  const SPEE_MOD = 12;
  const WEATH_MOD = 13;
  const DDC_ENGLISH_PLUS_DISTRACTED_DRIVING = 14;
  const DDC_ENGLISH_2 = 15;
  const DDC_SPANISH_PLUS_DISTRACTED_DRIVING = 16;
  const PTD = 17;
  
  protected function enrollInACourse()
  {
    ?>
    You are not currently signed up for a course.  To sign up,
    <a href="<?php echo PlatformConfig::findACourseUrl()?>">click here</a>.
    <?php
  }
  
    protected function registeredMessage()
    {
        if ($this->newest_enrollment->Course->course_type_id == A25_Record_Course::typeId_Spanish) {
            ?>
            <div style="clear: left;">Est&aacute;s inscrito en el curso <?php echo $this->newest_enrollment->Location->location_name ?>.
            Una vez que se haya recibido el pago, aparecer&aacute; un enlace aqu&iacute; que le permitir&aacute; tomar el curso.</div>
            <a class="action_link" href="<?php echo $this->cancellationLinkLocation($this->newest_enrollment); ?>">
              Haga clic aqu&iacute; para cancelar esta inscripci&oacute;n.</a>
            <?php
        }
        else {
            ?>
            <div style="clear: left;">You are signed up for the <?php echo $this->newest_enrollment->Location->location_name ?>
            course.  Once payment has been received, a link will appear here allowing you to take the course</div>
            <a class="action_link" href="<?php echo $this->cancellationLinkLocation($this->newest_enrollment); ?>">
              Click here to cancel this enrollment</a>
            <?php
        }
    }
  
  protected function completeMessage()
  {
    $return = '<p>Congratulations on competing the '
        . PlatformConfig::courseTitleFullHtml()
        . ' course. You may return to the course to print your certificate by
        clicking the button below.</p>';
    $return .= $this->upcomingCourseMessage($this->newest_enrollment);
    return $return;
  }
  
  protected function courseInfo()
  {
    if ($this->newest_enrollment && $this->newest_enrollment->isActive()) {
      if ($this->newest_enrollment->hasBeenAttended())
        echo $this->completeMessage();
      elseif ($this->student->getAccountBalance() <= 0)
        echo $this->upcomingCourseMessage($this->newest_enrollment);
      else 
        $this->registeredMessage();
    } else {
      $this->enrollInACourse();
    }
  }
  
  protected function upcomingCourseMessage($enroll)
  {
    $prerequisite = $this->fireBeforeCourseButton($enroll);
    if ($prerequisite) {
        return $prerequisite;
    }
    if ($this->newest_enrollment->Course->course_type_id == A25_Record_Course::typeId_Spanish) {
        $return = A25_DI::PlatformConfig()->onlinePrerequisitesSpanish;
        if ($this->newest_enrollment->course_id == self::DDC_SPANISH_PLUS_DISTRACTED_DRIVING) {
            $return .= '<p>' . htmlentities('IMPORTANTE: El Programa ADoD se compone de dos cursos. Deberá imprimir un certificado de finalización de cada curso.') . '</p>';
        }
    }
    else {
        $return = A25_DI::PlatformConfig()->onlinePrerequisites;
    }
    
    $loginId = $this->buildUserId();
    $password = $this->buildPassword();
    $phone = $this->student->home_phone;
    if (!$phone) {
        $phone = '0000000000';
    }
    
    $return .= '<form method="post" action="DDCOnline">
      <input type="hidden" name="accessCode" value="' . $this->getACode() . '">
      <input type="hidden" name="loginId" value="' . $loginId . '">
      <input type="hidden" name="password" value="' . $password . '">
      <input type="hidden" name="firstName" value="' . preg_replace("/[^A-Za-z]/", '', $this->student->first_name) . '">
      <input type="hidden" name="lastName" value="' . preg_replace("/[^A-Za-z]/", '', $this->student->last_name) . '">
      <input type="hidden" name="addressLine1" value="' . preg_replace("/[^0-9A-Za-z\s]/", '', $this->student->address_1) . '">
      <input type="hidden" name="addressLine2" value="' . preg_replace("/[^0-9A-Za-z\s]/", '', $this->student->address_2) . '">
      <input type="hidden" name="city" value="' . preg_replace("/[^A-Za-z\s]/", '', $this->student->city) . '">
      <input type="hidden" name="state" value="' . $this->student->state . '">
      <input type="hidden" name="zipCode" value="' . $this->student->zip . '">
      <input type="hidden" name="emailAddress" value="' . $this->student->email . '">
      <input type="hidden" name="phoneNumber" value="' . $phone . '">
      <input type="submit" style="font-size: 12px" value="Click here to access the course">
    </form>';

    $return .= '<p>Provided by:</p>';
    $return .= '<img src="' . A25_Link::to(A25_DI::PlatformConfig()->onlineProviderImagePath) . '" style="vertical-align: middle; max-height: 60px;" />'
        . ' with <img src="' . A25_Link::to('/images/nsc.png') .'" style="vertical-align: middle; max-height: 60px;"/>';
    $return .= '<p style="font-size: 10px;">Trouble accessing the course?  If'
. ' you changed your password on the NSC training site the button above may not'
. ' work.  You can <a href="https://training.nsc.org/csp">access their login'
. ' form directly</a> and enter your new password. Your Login ID is ' . $this->buildUserId() . '</p>'
. '<p style="font-size: 10px;">If you signed up before June 25, 2025, please'
. ' <a href="https://training.safetyserve.com/finesource/attend/User_login_corp.aspx?ugid=A0056534">access their legacy login'
. ' form directly</a> and use ' . $this->student->userid . ' as your Login ID and ' . $this->student->zip . $this->student->userid . ' as your password.</p>';
    
    return $return;
  }
  
  protected function getACode()
  {
    switch ($this->newest_enrollment->course_id)
    {
      case self::DDC_ENGLISH:
        return 'EXDDC43$';
      case self::DDC_MOTORCYCLE:
        return 'EXMTOR9$';
      case self::DDC_SPANISH:
        return 'EXDDCSP$';
      case self::DISTRACTED_DRIVING:
        return 'EXDISTR$';
      case self::AFO_MOD:
        return 'AFOMOD$';
      case self::AGG_MOD:
        return 'AGGMOD$';
      case self::BPLS_MOD:
        return 'BPLSMOD$';
      case self::FATI_MOD:
        return 'FATIMOD$';
      case self::IMPA_MOD:
        return 'IMPAMOD$';
      case self::INTE_MOD:
        return 'INTEMOD$';
      case self::LANE_MOD:
        return 'LANEMOD$';
      case self::SPEE_MOD:
        return 'SPEEMOD$';
      case self::WEATH_MOD:
        return 'WEATHMOD$';
      case self::DDC_ENGLISH_PLUS_DISTRACTED_DRIVING:
        return 'EX10XDTII$';
      case self::DDC_ENGLISH_2:
        return 'EXDDC43$';
      case self::DDC_SPANISH_PLUS_DISTRACTED_DRIVING:
        return 'EX10SDTII$';
      case self::PTD:
        return 'EXPTDMODS$';
    }
  }
  
  protected function reservationMessage() 
  {
  }
  
  protected function buildPassword()
  {
    $password = $this->student->zip . $this->student->userid;
    if ($this->student->student_id >  A25_DI::PlatformConfig()->studentIdToStartNewPassword) {
        $password .= '8';
    }
    $password .= '$qX' . substr($this->student->password, 1, 10);
    if (strlen($password) > 30) {
        $password = substr($password, -30);
    } 
    return $password;
  }
  
  private function buildUserId()
  {
    $loginId = $this->student->userid;
    if ($this->student->student_id >  A25_DI::PlatformConfig()->studentIdToStartNewUserId) {
        $loginId .= PlatformConfig::STATE_ABBREV;
    }
    $loginId = str_pad($loginId, 5, 'A');
    return $loginId;
  }
  
  protected function enrollmentHistory()
  {
    return;
  }
  
  public function kickOutIfNecessary()
  {
  }
  
  protected function paymentNotes()
  {
  }

    private function fireBeforeCourseButton($enroll)
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_OnlineCourseAccount) {
                return $listener->beforeCourseButton($enroll);
            }
        }
    }
}
