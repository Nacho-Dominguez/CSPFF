<?php

/**
 * This class is based on the "Abstract Factory" pattern, as explained in the
 * Gang of Four book, "Design Patterns".
 */
abstract class A25_Factory
{
  public function StudentMailer()
  {
    return new A25_StudentMailer();
  }
  
  public function DonationReceipt($amount)
  {
    return new A25_Envelope(new A25_EmailContent_DonateNowReceipt($amount));
  }
  
  public function HtmlBodyGenerator()
  {
    return new A25_Remind_HtmlBodyGenerator();
  }
  
  public function KickOut()
  {
    return new A25_Remind_Students_KickOut();
  }
  
  abstract public function Account($student);
  abstract public function BusinessRules();
  abstract public function ReasonForEnrollment();
}
