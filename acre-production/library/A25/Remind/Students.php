<?php

abstract class A25_Remind_Students extends A25_Remind
{
  /**
   * @param type $record - must implement getStudent()
   */
  public function sendToIndividual($record)
  {
    $this->markSent($record);
    $subject = $this->subject();
    $body = $this->body($record);
    $alt_body = $this->alt_body($record);
    
    A25_DI::Factory()->StudentMailer()->send($record->getStudent(), $subject, $body,
      true, $alt_body);
  }
  
  /**
   * @param A25_Record_Enroll $enroll
   * @return string
   */
  protected function alt_body(A25_Record_Enroll $enroll)
  {
    $body = $this->body($enroll);
    $converter = new A25_HtmlTextConverter();
    return $converter->wrapText($converter->stripHtml($body));
  }
  
  protected abstract function markSent($enroll);
  
  protected abstract function subject();
  
  protected abstract function body(A25_Record_Enroll $enroll);
}