<?php

class A25_Remind_HtmlBodyGenerator
{
  public function header(A25_Record_Student $student, $title)
  {
    ob_start();
    require dirname(__FILE__) . '/Header.phtml';
    return ob_get_clean();
  }
  public function footer()
  {
    ob_start();
    require dirname(__FILE__) . '/Footer.phtml';
    return ob_get_clean();
  }
  public function paymentDueBox(A25_Record_Student $student,
      A25_Record_Course $course, A25_Record_Enroll $enroll)
  {
    ob_start();
    require dirname(__FILE__) . '/PaymentDueBox.phtml';
    return ob_get_clean();
  }
  public function slowPaymentInstructions(A25_Record_Student $student,
      A25_Record_Course $course, A25_Record_Enroll $enroll)
  {
    ob_start();
    require dirname(__FILE__) . '/SlowPaymentInstructions.phtml';
    return ob_get_clean();
  }
    public function cancelPolicy($course) {
        $return = '<p style="font-size: 10px; color: #999;">';
        if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            $return .= 'Todos los pagos no son reembolsables y no pueden '
. 'transferirse a la cuenta de otro estudiante.  Sin embargo, si cancelas tu '
. 'inscripci&oacute;n y te vuelves a inscribir en una clase diferente, tu pago '
. 'se aplicar&aacute; autom&aacute;ticamente a la nueva inscripci&oacute;n.';
        }
        else {
            $return .= 'All payments are non-refundable and cannot be transferred to another student\'s
        account.  However, if you cancel your enrollment and re-enroll in a different
        class, your payment will automatically be applied to the new enrollment.';
        }
        $return .= '</p>';
        return $return;
    }
    public function courseComments(A25_Record_Course $course)
    {
        $return = '';
        if (A25_DI::PlatformConfig()->hideCourseNotesForVirtualClasses == false
                || $course->Location->virtual == false) {
            $return .= '<span style="text-align: left;">'
                    . A25_DI::PlatformConfig()->courseCommentsPrepend() . '</span>';
        }
        $return .= '<p style="text-align: left;">' . $course->course_description
            . '</p>' . $this->fireAfterCourseComments();
        return $return;
    }
  public function fireAfterCourseComments()
  {
    foreach (A25_ListenerManager::all() as $listener) {
      if ($listener instanceof A25_ListenerI_AppendCourseComments) {
        return $listener->afterCourseComments();
      }
    }
  }
}
