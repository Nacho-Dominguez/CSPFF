<?php
class A25_Html
{
	public static function studentAccountInformation(A25_Record_Student $student)
	{
		$return  = '<table width="100%" cellpadding="0" cellspacing="2" border="0">
	<tr>
		<td colspan="2">Go to your <a href="' . PlatformConfig::accountUrl() . '">My
		Account</a> area to review and edit your profile, enrollment
		information and order history.</td>
	</tr>
	<tr>
		<td class="formlabel"><strong>Student ID:</strong></td>
		<td><strong>' . $student->student_id . '</strong></td>
	</tr>
	<tr>
		<td class="formlabel">Name:</td>
		<td>' . $student->firstLastName() . '</td>
	</tr>
	<tr>
		<td class="formlabel">Phone:</td>
		<td>' . $student->home_phone . '</td>
	</tr>
	<tr>
		<td class="formlabel">Email:</td>
		<td>' . $student->email . '</td>
	</tr>
	<tr>
		<td class="formlabel" align="top">Student Address:</td>
		<td>' . $student->fullAddress() . '
		</td>
	</tr>
</table>';
		return $return;
	}
  
/**
 * @todo-soon - clean up the duplication in displaying dates & times 
 */
  public static function courseDateAndLocation(A25_Record_Course $course)
  {
    $location = $course->Location;
    $return = "<b>" . $course->formattedDate('course_start_date', 'l, F j, Y')
 . "</b><br/>" . $course->formattedDate('course_start_date', 'g:i a')
 . " &ndash; " . date('g:i a', strtotime($course->getEndTime()));
    if ($location->virtual) {
        $return .= "<br/>" . A25_DI::PlatformConfig()->timezone;
    }
    $return .= "<br/><p>" . $location->location_name . "<br/>";
    if (!$location->virtual) {
        $return .= $location->address_1 . "<br/>";
        if (!empty($location->address_2)) {
          $return .= $location->address_2 . '<br/>';
        }
        $return .= $location->city . ', ' . $location->state . ' ' . $location->zip
        . '</p>
       <p>' . $location->googleMap() . 
       '</p>';
    }
    return $return;
  }
  
  public function courseMessage($course, $hideCourseNotesForVirtualClasses = false)
  {
      $location = $course->Location;
      $return = '';
    if (A25_DI::PlatformConfig()->courseIsOnline == false) {
        $return .= '<div style="background-color: #f2f2f2; padding: 12px;
        text-align: center; color: #333; max-width: 280px; clear: left;">'
        . self::courseDateAndLocation($course) . '</div>';
    }
    // Don't show virtual course notes on course info page for certain states
    if ($hideCourseNotesForVirtualClasses == false || $location->virtual == false
            || A25_DI::PlatformConfig()->hideCourseNotesForVirtualClasses == false) {
        $return .= '<p><b>';
        if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            $return .= 'Notas del curso';
        }
        else {
            $return .= 'Course notes';
        }
    $return .= ':</b></p>';
        $generator = new A25_Remind_HtmlBodyGenerator();
        $return .= $generator->courseComments($course);
        if ($course->zoom_link) {
            $return .= '<br/>Zoom link: <a href="' . $course->zoom_link . '">' . $course->zoom_link . '</a>';
        }
    }
    $return .= '<p><b>';
    if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
        $return .= 'Notas de ubicaci&oacute;n';
    }
    else {
        $return .= 'Location notes';
    }
    $return .= ':</b></p>' . $location->description;
    return $return;
  }

    public function certificateMessage($course, $student)
    {
        $newest_enrollment = $student->getNewestEnrollment();
        if ($course->Location->virtual == true) {
            if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
                return A25_DI::PlatformConfig()->courseInfoCertificateMessageVirtualSpanish;
            }
            return A25_DI::PlatformConfig()->courseInfoCertificateMessageVirtual;
        }
        if ($newest_enrollment->hasFeeOfType(A25_Record_OrderItemType::typeId_LateFee)
        && !$newest_enrollment->Course->isPast()) {
            return '<p><b>Proof of completion:</b><br/>'
            . 'Since you enrolled within 24 hours of the class, it is possible '
            . 'that the instructor will not have your Certificate of Completion '
            . 'pre-printed. If the instructor does not have your certificate at the '
            . 'class, please call us at ' . PlatformConfig::phoneNumber . ' during normal business hours '
            . 'to arrange for us to mail it to you, or for you to come by the office to pick it up.</p>';
        } else {
            if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
                return A25_DI::PlatformConfig()->courseInfoCertificateMessageSpanish;
            }
            return A25_DI::PlatformConfig()->courseInfoCertificateMessage;
        }
    }
}
?>
