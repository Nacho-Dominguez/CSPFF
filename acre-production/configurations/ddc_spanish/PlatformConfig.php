<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '4qc9Vb9Ug';
    const AUTHORIZE_NET_TRAN_KEY = '4cg8ZGq9x79Qy28h';

    public $acceptChecks = false;
    public $acceptOnlyCreditCards = true;
    public $requireEmail = true;

  // This setting is necessary so that the yellow 'selected state' box is
  // hidden
    const isAState = false;
    const STATE_ABBREV = 'co';

    public $courseIsOnline = true;
    public $affid = 'A0056534';

    public $kickOutAfterDeadline = 'never';

    public function colorScheme()
    {
        return new A25_ColorScheme_Blue();
    }

    const courseTitle = 'DDC Online (Español)';
    const courseTitleFull = 'DDC Online (Español)';
    const siteTitle = 'DDC Online (Español)';

    const defaultCourtFee = 69;

    const minAge = 14;
    const maxAge = 9999;

    const messageSenderId = 63;

    public function findACourseUrl()
    {
        return "/component/option,com_course/task,confirm/course_id,3/Itemid,19/";
    }

    public function accountUrlDirect()
    {
        return A25_Link::to('/account');
    }

    const reasonTypeId_PendingLegalMatter_number = 6;

    public $sendReminders = false;
    
    public $reasonForEnrollmentCourtOrderText = '<i>
  If you are taking this course because of an order or agreement with a court,
  prosecutor, or other criminal justice system organization, please select <b>Court
  Order or Pending Legal Matter</b> and then select the referring court/organization.
  <br/><br/>Please note that this course does not qualify you to obtain a driving permit.</i>';
    
    public static function loginToEnrollText($course)
    {
        return 'Para inscribirse en el curso ' . PlatformConfig::courseTitleHtml() . ' , debe iniciar sesión. Si aún no tiene una cuenta, regístrese a continuación.';
    }
    
    public $studentIdToStartNewPassword = 85859;
    public $studentIdToStartNewUserId = 85871;
}
