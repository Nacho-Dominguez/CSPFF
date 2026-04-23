<?php

foreach (glob(dirname(__FILE__) . '/EnrollmentStatus/*.php') as $file)
  require_once $file;

/**
 * This class is an experiment in a new way to "declaratively" handle some of
 * the business rules while avoiding the complexities that come from being tied
 * to Doctrine.
 * 
 * Since enrollment status types are constant, there is no need
 * to use Doctrine, because we won't be updating them. We just need to know
 * their properties.
 */
abstract class A25_EnrollmentStatus
{
  public abstract function statusId();
  public abstract function isActive();
  public abstract function occupiesSeat();
  public abstract function allowsPaymentEffectsBeforeCourse();
  public abstract function allowsPaymentEffectsAfterCourse();
  public abstract function preEnrollmentEmail();
  public abstract function canCountAsPaid();
  public abstract function reservationIsTemporary();
  public abstract function isComplete();
  
  /**
   * A student is generally only allowed to be enrolled in 1 course at a time.
   * If true, an enrollment with this status will block the student from making
   * a new enrollment.
   */
  public abstract function blocksOtherEnrollments();
  
  /**
   * @todo-active_enroll If an enrollment is "active", it really means that the
   * student would owe tuition.  Verify that no usages of it assume a different
   * meaning, then use a better word than "Active" in all of the methods that
   * are related to Enrollments.
   */
  public function isInactive()
  {
    return (!$this->isActive());
  }
  
  /**
   * @todo-jon-low-small - rename this to wasAttendedIfCourseIsPast(), so that
   * we don't forget that if the course hasn't happened yet, we can't assume
   * that the enrollment was attended.
   */
  public abstract function wasAttended();
  
  public static function all() {
    $result = array();
    foreach (get_declared_classes() as $class) {
      if (is_subclass_of($class, 'A25_EnrollmentStatus'))
        $result[] = new $class;
    }
    
    return $result;
  }
}
