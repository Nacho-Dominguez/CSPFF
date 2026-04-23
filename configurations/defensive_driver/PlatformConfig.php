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

    const courseTitle = 'Distracted Driving';
    const courseTitleFull = 'Distracted Driving';
    const siteTitle = 'Distracted Driving';

    const defaultCourtFee = 45;

    const minAge = 14;
    const maxAge = 9999;

    const messageSenderId = 63;

    public function findACourseUrl()
    {
        return "/component/option,com_course/task,confirm/course_id,4/Itemid,19/";
    }

    public function accountUrlDirect()
    {
        return A25_Link::to('/account');
    }

    const reasonTypeId_PendingLegalMatter_number = 6;

    public $sendReminders = false;
    
    public static function creditCardRequirementMessage()
    {
        return 'I understand that once I begin the course, I cannot be issued a refund';
    }
    
    public static function loginToEnrollText($course)
    {
        return 'In order to enroll for the ' . PlatformConfig::courseTitleHtml() . ' course, you must log in. If you do not have an account yet, please register below.';
    }
    
    public $studentIdToStartNewPassword = 85859;
    public $studentIdToStartNewUserId = 85871;
}
