<?php

class A25_Remind_Locations extends A25_Remind
{
  public function whom()
  {
    $locquery = Doctrine_Query::create()
        ->select('*')
        ->from('A25_Record_Location l')
        ->where('l.published = 1');

    $locations = $locquery->execute();

    $coursequery = Doctrine_Query::create()
        ->select('*')
        ->from('A25_Record_Course c')
        ->where('c.course_start_date > ?', A25_Functions::formattedDateTime());

    $courses = $coursequery->execute();
    
    $return = array();

    foreach ($locations as $location) {
      $sum = 0;
      foreach ($courses as $course) {
        if ($course->location_id == $location->location_id
            && $course->course_start_date < A25_Functions::formattedDateTime($location->getSetting('alert_days') . ' days')) {
          $sum = $sum + $course->course_capacity;
        }
      }
      if ($sum < $location->getSetting('alert_seats') && $location->getSetting('alert_days'))
        $return[] = $location->location_id;
    }
    return $return;
  }
  
  protected function sendToIndividual($location_id)
  {
    $location = A25_Record_Location::retrieve($location_id);
    $subject = self::emailSubject($location);
    $body = self::emailBody($location);
    // Comment out this loop to deactivate emails to instructors
    $users_xref = $location->LocationUsers;
    foreach ($users_xref as $user_xref) {
      $user = A25_Record_User::retrieve($user_xref->user_id);
      if ($user->block == 0)
        $user->sendMessage($subject, $body);
    }
		A25_DI::Mailer()->mail(ServerConfig::adminEmailAddress, $subject, $body, 0);
  }

	/**
	 * @return string
	 */
	private static function emailSubject(A25_Record_Location $location)
	{
		return PlatformConfig::courseTitleHtml() . ': Not enough classes at '
        . $location->location_name;
	}

	/**
	 * @return string
	 */
	private static function emailBody(A25_Record_Location $location)
	{
		return 'Dear' . PlatformConfig::courseTitle . 'Instructor:

You are receiving this automated message because you are listed as an instructor with class posting permissions at "'
. $location->location_name . '" location.

Fewer than ' . $location->alert_seats . ' seats are available at '
. $location->location_name . ' in the next ' . $location->alert_days . ' days.

Please act upon this message by consulting other local instructors and by causing additional classes to be posted as prescribed.

If you have any questions, comments or concerns, please do not hesitate to contact '
. A25_DI::PlatformConfig()->locationSeatAlertContact . '.

Thank you for your prompt attention.';
	}
}
