<?php

class A25_Remind_SmsToCourse extends A25_Remind
{ 
  private $course_id;
  private $message;
  
  public function __construct($course_id, $message)
  {
    $this->course_id = $course_id;
    $this->message = PlatformConfig::courseTitle . ' update: ' . $message . ' Reply STOP to unsubscribe.';
  }
  
	protected function whom()
	{
		$q = A25_Plugin_SmsMessages::queryStudentsWhoCanReceiveInCourse(
        $this->course_id);
		return $q->execute();
	}
  
  protected function sendToIndividual($record)
  {
    $sender = A25_SmsSender::instance();
    
    if ($record->home_sms)
      $sender->send($this->message, $record->home_phone);
    
    if ($record->work_sms)
      $sender->send($this->message, $record->work_phone);
  }
}