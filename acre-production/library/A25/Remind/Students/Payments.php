<?php

/**
 * @todo-jon-small-low - Move PaymentsBody.phtml and
 * PaymentsAltBody.phtml into PaymentsBodies/body.phtml and
 * alt_body.phtml, respectively.  Basically, they should be done like
 * DonationReceipt in the Donation Plugin was done.
 */
abstract class A25_Remind_Students_Payments extends A25_Remind_Students
{ 
  protected function subject()
  {
    return A25_EmailContent::wrapSubject('Payment reminder');
  }
  
  /**
   * @param A25_Record_Enroll $enroll
   * @return string
   */
  protected function body(A25_Record_Enroll $enroll)
  {
    ob_start();
    require dirname(__FILE__) . '/PaymentsBody.phtml';
    return ob_get_clean();
  }
  
  abstract protected function beginningOfReminderWindow();
  
  abstract protected function endOfReminderWindow();
}