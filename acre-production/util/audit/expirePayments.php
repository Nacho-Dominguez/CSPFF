<?php

require_once dirname(__FILE__) . '/../../autoload.php';

$students = Doctrine_Query::create()
		->from('A25_Record_Student s')
    ->innerJoin('s.Payments p')
		->where('s.calc_balance < 0')
    ->execute();

$oneyearago = strtotime('1 year ago');

echo "One year ago was " . date('Y-m-d', $oneyearago) . "\n";

echo 'Total students with credits: ' . $students->count() . "\n";

$count = 0;

foreach ($students as $student) {
  $lastpayment = $student->Payments->end();
  if (strtotime($lastpayment->created) < $oneyearago)
  {
    $balance = $student->getAccountBalance();
    if ($balance < 0) {
      $last_enroll = $student->Enrollments->end();
      
      // This section would really be better as its own function, or maybe even
      // as its own class, possible A25_AddFeesWhenPaymentExpires, much like the
      // other classes that add fees, such as A25_AddFeesWhenPaying.
      $fee = new A25_Record_OrderItem();
      $fee->type_id = A25_Record_OrderItemType::typeId_ExpiredPayment;
      $fee->quantity = 1;
      $fee->unit_price = -($balance);
      $fee->order_id = $last_enroll->Order->order_id;
      $payment_time = strtotime($lastpayment->created . '+ 1 year');
      $fee->created = date('Y-m-d H:i:s', $payment_time);
      $fee->date_paid = date('Y-m-d', $payment_time);
      $fee->save();
      
      // This part won't be necessary in the weekly cron:
      $student->refresh(true);
      $fee->refresh(true);
      $fee->updateCalculatedValues();
      $fee->save();
      $student->markAppropriateOrdersAndLineItemsAsPaid();
      $student->updateCalculatedValues();
      $student->save();
      
      if ($student->getAccountBalance() != 0)
        echo "WARNING: account balance of this student is not zero! - ";
      
      echo $student->student_id . "\n";
      $count++;
    }
  }
}

echo 'Total students with expired credits: ' . $count . "\n";