<?php

/**
 * Represents a row from DB table jos_order_item.
 */
class A25_Record_OrderItem extends JosOrderItem
{
  const waiveType_Admin = 1;
  const waiveType_Student_SelfSend = 2;
  const waiveType_Student_CourtSend = 3;
  const waiveType_StudentConfirmed = 4;

  /**
   * @todo-#181 - Change this to a function (especially all calls to it), test,
   * and commit. Then, rather than having a list defined here, scan through all
   * subclasses and add them if their isRefundable() is true.
   * (Before completing this, accrualDateBasedOn() will have to have been
   * created in all subclasses)
   */
  static $refundableList = array(A25_Record_OrderItemType::typeId_CourseFee);

  /**
   * @todo-#181 - Change this to a function (especially all calls to it), test,
   * and commit. Then, rather than having a list defined here, scan through all
   * subclasses and add them if their accrualDateBasedOn() is 'course'.
   * (Before completing this, accrualDateBasedOn() will have to have been
   * created in all subclasses)
   */
  static $incomeOnCreationDate = array(
    A25_Record_OrderItemType::typeId_CreditCardFee,
    A25_Record_OrderItemType::typeId_LateFee,
    A25_Record_OrderItemType::typeId_ReplaceCertFee,
    A25_Record_OrderItemType::typeId_ReturnCheckFee,
    A25_Record_OrderItemType::typeId_Donation
  );
  
  /**
   * @todo-#181 - Rather than having a list defined here, scan through all
   * subclasses and add them if their neverRevenue() is true.
   * (Before completing this, neverRevenue() will have to have been
   * created in all subclasses)
   */
  static function neverRevenueList()
  {
    return array(A25_Record_OrderItemType::typeId_CourtSurcharge,
        A25_Record_OrderItemType::typeId_Donation);
  }

  /**
   * @param integer $id
   * @return A25_Record_OrderItem
   *
   * As part of goal 'php5_3', all declarations of this will be replaced with
   * a single definition in A25_Record.
   */
  public static function retrieve( $id)
  {
    return Doctrine::getTable('A25_Record_OrderItem')->find($id);
  }
  
  public static function factoryMethod($table)
  {
    $data = $table->getData();
    $type_id = $data['type_id'];
    $subclass = self::getSubclass($type_id);
    return new $subclass($table);
  }
  
  /**
   * @todo-scopeAndMakeIssue - Once all of the type id's have been moved out of
   * A25_Record_OrderItemType and into their subclasses, this should be
   * refactored to just be a loop through all subclasses until the right one is
   * found.
   */
  public static function getSubclass($type_id)
  {
    switch ($type_id) {
      case A25_Record_OrderItemType::typeId_CourseFee:
        return 'A25_Record_OrderItem_Tuition';
      case A25_Record_OrderItemType::typeId_LateFee:
        return 'A25_Record_OrderItem_LateFee';
      case A25_Record_OrderItemType::typeId_ReplaceCertFee:
        return 'A25_Record_OrderItem_ReplaceCertFee';
      case A25_Record_OrderItemType::typeId_ReturnCheckFee:
        return 'A25_Record_OrderItem_ReturnCheckFee';
      case A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows:
        return 'A25_Record_OrderItem_NoShowFee';
      case A25_Record_OrderItemType::typeId_CreditCardFee:
        return 'A25_Record_OrderItem_CreditCardFee';
      case A25_Record_OrderItemType::typeId_CourtSurcharge:
        return 'A25_Record_OrderItem_CourtSurcharge';
      case A25_Record_OrderItemType::typeId_MoneyOrderDiscount:
        return 'A25_Record_OrderItem_MoneyOrderDiscount';
      case A25_Record_OrderItemType::typeId_ExpiredPayment:
        return 'A25_Record_OrderItem_ExpiredPayment';
      case A25_Record_OrderItemType::typeId_Donation:
        return 'A25_Record_OrderItem_Donation';
        
      default:
        return 'A25_Record_OrderItem';
    }
  }

  public function check()
  {
    if ($this->order_id < 1)
      throw new A25_Exception_DataConstraint (
          "Invalid order_id for LineItem $this->item_id");
    if ($this->type_id < 1)
      throw new A25_Exception_DataConstraint (
          "Invalid type_id for LineItem $this->item_id");
    if ($this->quantity < 1)
      $this->quantity = 1;
    if ($this->unit_price < 0)
      throw new A25_Exception_DataConstraint (
          "Invalid unit_price for LineItem $this->item_id");
    return true;
  }

  public function updateCalculatedValues()
  {
    $this->updateCalculatedValue('calc_is_active', 'isActive');
    $this->updateCalculatedValue('calc_accrual_date', 'accrualDate');
  }

  /**
   *  This switch statement is used instead of the _order_item_type so the
   *  database does not need to be updated to add an OrderItemType
   */
  public function getTypeName()
  {
    return A25_Record_OrderItemType::getTypeName($this->type_id);
  }

  /**
   * @return bool
   */
  public function isActive()
  {
    if ($this->waived())
      return false;
    
    if ($this->isIndependentOfEnrollment())
      return true;

    if ($this->relatedIsDefined('Order'))
      return $this->Order->isActive();

    return false;
  }

  public static function nonCourseRevenue($alias)
  {
    return "$alias.type_id <> " . A25_Record_OrderItemType::typeId_CourseFee;
  }

  /**
   * Only a course fee is credited to the account balance when an order is
   * cancelled.
   * @return bool
   */
  public function isIndependentOfEnrollment()
  {
    return !(in_array($this->type_id, self::$refundableList));
  }

  public function chargeAmount()
  {
    if($this->waived())
      return 0;
    return $this->faceValue();
  }

  public function faceValue()
  {
    return $this->quantity * $this->unit_price;
  }

  public function markPaid()
  {
    $this->date_paid = date('Y-m-d');
  }
  
  public function isPaid()
  {
    return ($this->date_paid > 0 || $this->waived());
  }

  public function dateOfCompletedCourse()
  {
    if($this->getStatusId() == A25_Record_Enroll::statusId_completed)
      return $this->getCourse()->date();

    $enrollments = $this->getStudent()->Enrollments;
    foreach($enrollments as $enroll){
      if($enroll->xref_id <= $this->getEnrollment()->xref_id )
        continue;

      if($enroll->status_id == A25_Record_Enroll::statusId_completed)
        return $enroll->Course->date();
    }

    return null;
  }

  public function waive($type = self::waiveType_Admin)
  {
    // must do this so this item does not get reloaded in updateTotal()
    if ($this->relatedIsDefined('Order'))
      $this->Order->OrderItems;

    $this->waive_type = $type;

    $this->waive_date = date('Y-m-d');
  }
  public function waived()
  {
    return ($this->waive_type > 0);
  }
  public function waivedButUnconfirmed()
  {
    return ($this->waive_type == self::waiveType_Student_SelfSend
        || $this->waive_type == self::waiveType_Student_CourtSend);
  }
  public function waivedAndConfirmed()
  {
    return ($this->waive_type == self::waiveType_StudentConfirmed
        || $this->waive_type == self::waiveType_Admin);
  }

  public function unwaive()
  {
    $this->waive_date = null;
    $this->waive_type = null;
  }

  /**
   * @todo-scopeAndMakeIssue - Move all data for determining whether a Fee Type
   * is part of Course Revenue into the subclasses, such as OrderItem_Tuition.
   * This will probably require some re-work for state customizations.  The best
   * way to let a state override the setting is probably to put a Listener into
   * the function declaration for Types that can be different.
   */
  public static function elligibleForCourseRevenue($alias)
  {
    return "$alias.type_id IN (" .
          implode(',',PlatformConfig::orderItemTypesElligibleForCourseRevenue()) . ')';
  }
  
  public function getStudent()
  {
    if ($this->Order)
      return $this->Order->getStudent(); 
  }

  public function studentId()
  {
    if ($this->getEnrollment())
      return $this->getEnrollment()->student_id;
  }

  public function enrollmentId()
  {
    return $this->Order->xref_id;
  }

  public function courseId()
  {
    if ($this->getEnrollment())
      return $this->getEnrollment()->course_id;
  }

  public function courseDatetime()
  {
    if ($this->relatedIsDefined('Order'))
      return $this->Order->courseDatetime();
  }

  public function wasAttended()
  {
    if ($this->relatedIsDefined('Order'))
      return $this->Order->wasAttended();
  }

  public function getStatusId()
  {
    if ($this->getEnrollment())
      return $this->getEnrollment()->status_id;
  }
  
  public function getCourt()
  {
    if ($this->Order)
      return $this->Order->getCourt();
  }
  
  public function getEnrollment()
  {
    if ($this->relatedIsDefined('Order'))
      return $this->Order->Enrollment;
  }
  
  public function getCourse()
  {
    if ($this->relatedIsDefined('Order'))
      return $this->Order->getCourse();
  }
  
  public function accrualDate()
  {
    if (!$this->isActive())
      return null;

    return $this->accrualDateForActiveItem();
  }
  
  protected function accrualDateForActiveItem()
  {
    // Implement in subclass
  }
}
