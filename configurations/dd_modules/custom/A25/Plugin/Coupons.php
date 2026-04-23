<?php

class A25_Plugin_Coupons implements A25_ListenerI_Doctrine,
    A25_ListenerI_CreditType, A25_ListenerI_StudentConfirmationFields,
    A25_ListenerI_SaveEnrollment
{
  public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
  {
    if ($doctrineRecord instanceof A25_Record_CreditType) {
      $doctrineRecord->hasColumn('coupon_code', 'string', 30, array(
          'type' => 'string','type' => 'string',
          'length' => 30,
          'fixed' => false,
          'unsigned' => false,
          'primary' => false,
          'notnull' => false,
          'autoincrement' => false,
          ));
      $doctrineRecord->hasColumn('discount', 'decimal', 6, array(
          'type' => 'decimal',
          'length' => 6,
          'fixed' => false,
          'unsigned' => false,
          'primary' => false,
          'default' => '0.00',
          'notnull' => false,
          'autoincrement' => false,
          'scale' => '2',
          ));
    }
  }
  
  public function creditTypeFormField($form)
  {
    $tooltip = new A25_Include_Tooltip();
    $tooltip->load();
    
    $coupon_code = new A25_Form_Element_Text('coupon_code');
    $coupon_code->setLabel('Coupon Code <a href="javascript:void()" '
        . 'rel="tooltip" title="You may set a coupon code that students can '
        . 'enter when registering to receive this credit.">'
        . '<img src="' . A25_Link::to('/images/M_images/con_info.png') . '"/></a>')
        ->setAttrib('maxlength', 30)
        ->addValidator(new Zend_Validate_Alnum())
        ->addValidator(new Zend_Validate_StringLength(0, 30))
        ->addFilter(new Zend_Filter_StringToUpper());
    $coupon_code->getDecorator('Label')->setOption('escape', false);
    
    $form->addElement($coupon_code);
    
    $discount = new A25_Form_Element_Text_Amount('discount');
    $discount->setLabel('Coupon Amount')
        ->setRequired(false)
        ->setAttrib('maxlength', 6)
        ->addValidator(new Zend_Validate_Float());
    
    $form->addElement($discount);
  }
  
  public function afterReasonForEnrollment(A25_Record_Student $student, A25_Record_Course $course)
  {
    ?>
    <div class="row" style="margin-top: 8px;">
      <div class="col-sm-4">Coupon Code (optional):</div>
      <div class="col-sm-8"><input type="text" name="coupon" id="coupon" size="20"
        maxlength="30" class="inputbox"/></div>
    </div>
    <?php
  }
  
  public function duringMakeChangesPermanent(A25_Record_Enroll $enroll)
  {
    $code = substr($_REQUEST['coupon'],0,30);
    $code = strtoupper($code);
    $code = preg_replace('/[^A-Z0-9]+/', '', $code);
    if (empty($code)) {
        return;
    }
    if (!A25_DI::PlatformConfig()->allowCouponForCourtOrdered) {
        if ($enroll->reason_id == A25_Record_ReasonType::reasonTypeId_CourtOrdered) {
            return;
        }
    }
    
    $coupon = $this->getCoupon($code);
    
    if ($coupon->credit_type_id && !$this->alreadyHasCoupon($enroll, $coupon))
      $this->createCredit($enroll, $coupon);
  }
  
  protected function alreadyHasCoupon($enroll, $coupon)
  {
    $credits = $this->existingCredits($enroll);
    
    if (!$credits)
      return false;
      
    $current = $enroll;
    while ($current = $current->previousEnrollment()) {
      if ($current->wasAttended())
        return false;
      
      foreach ($credits as $credit) {
        if ($credit->xref_id == $current->xref_id
            && $credit->credit_type_id == $coupon->credit_type_id)
          return true;
      }
    }
    
    return false;
  }
  
  protected function existingCredits($enroll)
  {
    $query = Doctrine_Query::create()
        ->from('A25_Record_Credit c')
        ->where('c.student_id = ?', $enroll->student_id);
    
    return $query->execute();
  }
  
  protected function getCoupon($code)
  {
    $query = Doctrine_Query::create()
        ->from('A25_Record_CreditType c')
        ->where('c.coupon_code = ?', $code);
    
    $credits = $query->execute();
        
        $query2 = Doctrine_Query::create()
                ->select('SUM(c.credit_value) AS credit_used')
                ->from('A25_Record_CreditType ct')
                ->leftJoin('ct.Credit c')
                ->where('ct.coupon_code = ?', $code);
        
        $used_amount = $query2->execute();
        
        $amount_left = $credits[0]->total_value - $used_amount[0]->credit_used;
        if ($amount_left >= $credits[0]->discount)
        {
            return $credits[0];
        }
  }
  
  protected function createCredit($enroll, $coupon)
  {
    $pay = new A25_Record_Pay();
    $pay->amount = $coupon->discount;
    $pay->student_id = $enroll->student_id;
    $pay->pay_type_id = A25_Record_Pay::typeId_ScholarshipCredit;
    $pay->order_id = $enroll->Order->order_id;
    $pay->xref_id = $enroll->xref_id;
    $pay->checkAndStore();
    $pay->associateWithScholarship($coupon->credit_type_id);
    $pay->getStudent()->updateOrdersAndEnrollmentsAfterPayment();
  }
}

set_include_path(
	ServerConfig::webRoot . '/plugins/Coupons' . PATH_SEPARATOR
	. get_include_path()
);