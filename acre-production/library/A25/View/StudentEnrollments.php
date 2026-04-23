<?php
class A25_View_StudentEnrollments extends A25_StrictObject
{
	private $student;

	public function __construct(A25_Record_Student $student)
	{
		$this->student = $student;
	}
	/**
	 * Show a table with student enrollment information
	 * @author Christiaan van Woudenberg
	 * @version July 23, 2006
	 *
	 * @return void
	 * @note Revised showEnrollment
	 */
	public function run()
	{
		$str = '';
		$str = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="striped"><tbody>';

		if (!count($this->student->Enrollments)) {
			$str .= '<tbody><tr><td>This student is not enrolled for any courses.</td></tr></tbody>';
		} else {
			$str .= '<thead><tr><td>ID</td><td>Status</td><td>Location</td><td>Date/Time</td><td width="5%">Cert.</td><td width="5%">Cost</td><td width="5%">Pay</td><td width="5%">Cancel</td></tr></thead>';
			$str .= '<tbody>';

			$user = A25_DI::user();

			foreach ($this->student->Enrollments as $enroll) {
				$certLink = '';

        /**
         * @todo-jon-medium-small - refactor the comparision for 'student' or
         * 'completed' into new function $enroll->eligibleForInstructorPrinting(),
         * using the new EnrollmentStatus classes to "declare" whether each type
         * is eligible.
         */
				if ($enroll->status_id == A25_Record_Enroll::statusId_student
					|| $enroll->status_id == A25_Record_Enroll::statusId_completed
					|| $user->isAdminOrHigher()
					)
				{
					$certLink = '<a class="cert_button" href="'
						. A25_Link::to('/administrator/components/com_course/'
							. PlatformConfig::certPrinter . '?id=' . $enroll->xref_id)
						. '" title="Print certificate." target="_blank"><img src="'
						. A25_Link::to('/images/M_images/printButton.png')
						. '" width="16" height="16" border="0" /></a>';
				}

				$enrollmentLink = '<a href="index2.php?option=com_student&task=enrollview&xref_id=' . $enroll->xref_id . '" title="View this course.">' . date(A25_Functions::PHP_DATE_FORMAT,strtotime($enroll->courseDatetime())) . '</a>';

                $payLink = '<a href="' . A25_Link::to('/administrator/index2.php?option=com_pay&task=payformA&xref_id=' . $enroll->xref_id).'" title="Process a payment for this student."><img src="' . A25_Link::to('/includes/js/ThemeOffice/dollar.png') . '" width="16" height="16" border="0" /></a>';

				//$cancelLink = (($s->status_id == 1) || ($s->status_id == 2)) ? '<a href="index2.php?option=com_student&task=cancelEnrollment&xref_id=' . $s->xref_id . '" title="Cancel this course enrollment."></a>' : '';
				$cancellation_location = 'index2.php?option=com_student&task=cancelEnrollment&xref_id=' . $enroll->xref_id . '&id=' . $enroll->student_id;
    		    $cancellation_text = 'Are you sure you want to Cancel this course enrollment? This action cannot be undone. \n\n If the student has already paid, the payment will be applied to a new course \nenrollment. Late fees are not applied to a new course enrollment.\n\n Click OK to Cancel this course.';
		        $cancelLink = ($enroll->isActive() && !$enroll->courseIsPast()) ? '<a href="javascript:if(confirm(\'' . $cancellation_text . '\')) location=\'' . $cancellation_location . '\'"><img src="' . A25_Link::to('/administrator/images/publish_x.png') . '" width="12" height="12" border="0" /></a>' : '';


				$str .= '<tr>'
					. '<td style="vertical-align: top;">' . $enroll->xref_id . '</td>'
					. '<td style="vertical-align: top;">' . $enroll->statusName() . '</td>'
					. '<td style="vertical-align: top;">' . $enroll->getLocationName() . '</td>'
					. '<td style="vertical-align: top;">' . $enrollmentLink . '</td>'
					. '<td style="vertical-align: top; text-align: center;">' . $certLink . '</td>'
					. '<td style="vertical-align: top; text-align: center;">($' . number_format($enroll->Order->totalAmount(), 2) . ')</td>'
					. '<td style="vertical-align: top; text-align: center;">' . $payLink . '</td>'
					. '<td style="vertical-align: top; text-align: center;">' . $cancelLink . '</td>'
					. '</tr>' . "\n";

				$orderItemViewClass = 'A25_View_StudentEnrollmentsOrderItem';
				if (A25_DI::User()->isAdminOrHigher())
					$orderItemViewClass .= '_ForAdmins';
				
				foreach($enroll->Order->OrderItems as $item) {
					$view = new $orderItemViewClass($item);
					$str .= $view->run();
				}
				$str .= '<tr><td style="vertical-align: top; text-align: right;"
					colspan="6"><a href="'
					. A25_Link::to('/administrator/index2.php?option=com_student&task=newFee&order_id=' . $enroll->Order->order_id)
					. '">Add new fee</a></td><td colspan="2"></td></tr>';
			}
			$str .= '</tbody>';
		}
		$str .= '</table>';
		return $str;
	}
}
?>
