<?php

/**
 * Most properties are defined in BaseJosCourse.php.  Those properties map
 * directly to database fields.  In addition, we have these properties:
 *
 * @property string $date
 * - the 'date' portion of course_start_date
 * - this is implemented as a property and not just as a function so that it can
 *   be used by A25_Form.
 * - see test_unit_A25_Record_Course_PropertyDateTest for more info on how it
 *   works
 *
 * @property string $end_time
 * - the time of day that the class ends
 * - not an actual value in the database, but calculated from the start time and
 *   the duration.
 * - this is implemented as a property and not just as a function so that it can
 *   be used by A25_Form.
 *
 * @property string $start_time
 * - the 'time' portion of course_start_time
 * - this is implemented as a property and not just as a function so that it can
 *   be used by A25_Form.
 */
class A25_Record_Course extends JosCourse implements A25_Interface_HaveSettings
{
	/**
	 * These constants represent the data stored in jos_course_status.
	 */
	const statusId_Open = 1;
	const statusId_Closed = 3;
	const statusId_Cancelled = 4;

	const typeId_Public = Config_CourseTypes::PUBLIC_COURSE;
	const typeId_Spanish = Config_CourseTypes::SPANISH;

	public function __construct($table = null, $isNewEntry = false) {
		parent::__construct($table, $isNewEntry);

		$this->hasAccessorMutator('date', 'getDate', 'setDate');
		$this->hasAccessorMutator('duration', 'getDuration', 'setDuration');
		$this->hasAccessorMutator('start_time', 'getStartTime', 'setStartTime');
		$this->hasAccessorMutator('end_time', 'getEndTime', 'setEndTime');
	}

  /**
   * Statuses for courses that are either still open for enrollment or still
   * require action from Instructors to close them out.
   *
   * This doesn't seem like the perfect variable name, because "active" can mean
   * a lot of things.  From a certain perspective, even close classes are
   * active.
   *
   * @var array
   */
  public static $activeStatuses = array(self::statusId_Open);

	/**
	 * @param integer $id
	 * @return A25_Record_Course
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_Course')->find($id);
    }

	public function id()
	{
		return $this->course_id;
	}

	protected function getDate($load = true)
	{
		return $this->formattedDate('course_start_date', 'm/d/Y');
	}

	protected function setDate($value, $load = true)
	{
		$this->course_start_date = A25_Functions::formattedDateTime(
				strtotime($value . ' '
						. $this->formattedDate('course_start_date','G:i:s')));
	}

	protected function getStartTime($load = true)
	{
		return $this->formattedDate('course_start_date','h:i A');
	}

	protected function setStartTime($value, $load = true)
	{
		$this->course_start_date = A25_Functions::formattedDateTime(
				strtotime($value . ' '
						. $this->formattedDate('course_start_date','Y-m-d')));
	}

	/**
	 * Here, we automatically convert from the format saved in the database to
	 * the format which will be useful to us.
	 */
	protected function getDuration($load = true)
	{
		$value = $this->_get('duration', $load);
		$value = preg_replace('/^(\d+\:\d+)\:\d+$/', '${1}', $value);

		return $value;
	}

	/**
	 * Here, we 'sanitize' the input, to make sure that nothing illegal is put
	 * in, and to automatically format different forms of input for storage in
	 * the database.  This is a basic implementation of the "Sanitized Property
	 * Pattern" (http://thomasalbright.tumblr.com/post/11871116127/sanitized-property)
	 */
	protected function setDuration($value, $load = true)
	{
		if (preg_match('/^\d+\:\d+(?:\:\d+)?$/', $value));
			// Do nothing, this means $value is valid already
		else if (preg_match('/^\d+$/', $value, $matches))
			$value = $matches[0] . ':00';
		else
			throw new A25_Exception_IllegalArgument();

		$this->_set('duration', $value, $load);
	}

	/**
	 * Once upgraded to PHP 5.3, this function could be -re-written in a much
	 * simpler fashion using DateTime::add()
	 */
	public function getEndTime($load = true)
	{
		$start_time = $this->getStartTime($load);
		$duration = $this->getDuration($load);

		if ($start_time == null || $duration == null)
			return null;

		$h = strtotime($start_time);
		$second = A25_Functions::durationToSeconds($duration);
		$convert = strtotime("+$second seconds", $h);
		return date('h:i A', $convert);
	}

	/**
	 * Once upgraded to PHP 5.3, this function could be re-written in a much
	 * simpler fashion using DateTime functions
	 */
	protected function setEndTime($value, $load = true)
	{
		$duration_in_seconds = strtotime($value) - strtotime($this->getStartTime());

		$hours = (int)($duration_in_seconds / 3600);
		$minutes = ($duration_in_seconds % 3600) / 60;
		$this->setDuration("$hours:$minutes", $load);
	}

	/**
	 * Checks object for correctness
	 * @author Christiaan van Woudenberg
	 * @version June 20, 2006
	 *
	 * @return boolean
	 */
	function check() {
    // If enrollment deadline has no units, add "hours"
    if (preg_match('/^\d+$/', $this->enrollment_deadline)) {
      $this->enrollment_deadline .= ' hours';
    }

		// check for valid start date
		if (trim($this->course_start_date == '') || $this->course_start_date == '0000-00-00 00:00:00') {
			$this->_error = "Course date cannot be empty.";
			return false;
		}

		return true;
	}
    
    private function printCourseTime() {
        if ($this->course_type_id == A25_Record_Course::typeId_Spanish) {
            return 'Horario del curso';
        }
        return 'Course Time';
    }
    
    private function printCourseLocation() {
        if ($this->course_type_id == A25_Record_Course::typeId_Spanish) {
            return 'Ubicaci&oacute;n del curso';
        }
        return 'Course Location';
    }

	/**
	 * Show a table with course information
	 * @author Christiaan van Woudenberg
	 * @version July 6, 2006
	 *
	 * @param string $mode
	 * @return string
	 */
	public function showCourseInfo( $mode = 'full' )
	{
		if (@!$this->course_id) {
			return false;
		}

		$location = $this->Location;
		$str = '';

		if ($mode == 'full') {
			$str = '<table width="100%" border="0">'
				. $this->timeInfoInTableRow()
				. '<tr>'
				. '<td class="formlabeltop">';
            $str .= $this->printCourseLocation();
            $str .= ':</td>'
				. '<td><strong>' . $location->location_name . '</strong><br />'
				. $location->address_1 . '<br />'
				;
			$str .= $location->address_2 ? $location->address_2 . '<br />' : '';
			$str .= $location->city . ', ' . $location->zip
 			. '<br />' . $location->googleMap()
 				. '</td>'
				. '</tr>';
			if ($location->phone) {
				$str .= '<tr><td class="formlabel">Phone Number:</td><td>' . $location->phone . '</td></tr>';
			}
			if ($location->contact) {
				$str .= '<tr><td class="formlabel">Contact:</td><td>' . $location->contact . '</td></tr>';
			}
			$str .= '<tr>'
				. '<td colspan="2">' . $location->description
				. '</tr>'
				. '</table>'
				;
		} elseif ($mode == 'timelocation') {
			$str = '<div class="row">'
// Changed to use bootstrap.  If other uses of timeInfoInTableRow() are also changed, we can refactor.
//				. $this->timeInfoInTableRow()
        . '<div class="col-sm-4" style="font-weight: bold;">';
            $str .= $this->printCourseTime();
            $str .= ':</div>'
        . '<div class="col-sm-8">' . $this->timeInfoHtml() . '</div>'
        . '</div>'
        . '<div class="row" style="margin-top: 12px;">'
				. '<div class="col-sm-4" style="font-weight: bold;">';
            $str .= $this->printCourseLocation();
            $str .= ':</div>'
				. '<div class="col-sm-8">' . $location->location_name . '<br />'
				. $location->address_1 . '<br />'
				;
			$str .= $location->address_2 ? $location->address_2 . '<br />' : '';
			$str .= $location->city . ', ' . $location->zip
 				. '</div>'
				. '</div>'
				;
		} elseif ($mode == 'simple') {
			$str =  '<strong>' . $location->location_name . '</strong><br />'
				. $location->address_1 . '<br />'
				;
			$str .= $location->address_2 ? $location->address_2 . '<br />' : '';
			$str .= $location->city . ', ' . $location->zip
				. '<p>' . $location->googleMap() . '</p>'
				;
		} elseif ($mode == 'email') {
			$addr2 = $location->address_2 ? '                    ' . $location->address_2 . "\n" : '';
            $str = $this->printCourseTime();
            $str .= ':  ' . $this->longDate() . ', '
				. $this->startsAt() . ' - ' . $this->endsAt() . "\n";
            $str .= $this->printCourseLocation();
            $str .= ':    ' . $location->location_name . "\n"
				. '                    ' . $location->address_1 . "\n"
				. $addr2
				. '                    ' . $location->city . ', ' . $location->zip . "\n\n"
			;
            if ($location->virtual == false) {
                $str .= 'Google Map:' . "\n" . $location->googleMapUrl() . "\n";
            }
		}

		return $str;
	}

	public function countEnrollmentStatuses()
	{
		$stats['Cancelled'] = 0;
		$stats['Completed'] = 0;
		$stats['Failed'] = 0;
		$stats['Paid No Show'] = 0;
		$stats['Unpaid No Show'] = 0;
		$stats['Pending'] = 0;
		$stats['Registered'] = 0;
		$stats['Student'] = 0;

		foreach($this->Enrollments as $enroll) {
			switch($enroll->status_id) {
				case A25_Record_Enroll::statusId_canceled:
					$stats['Cancelled']++;
					break;

				case A25_Record_Enroll::statusId_completed:
					$stats['Completed']++;
					break;

				case A25_Record_Enroll::statusId_failed:
					$stats['Failed']++;
					break;

				case A25_Record_Enroll::statusId_noShow:
					if($enroll->isPaid()) {
						$stats['Paid No Show']++;
					}
					else {
						$stats['Unpaid No Show']++;
					}
					break;

				case A25_Record_Enroll::statusId_pending:
					$stats['Pending']++;
					break;

				case A25_Record_Enroll::statusId_registered:
					$stats['Registered']++;
					break;

				case A25_Record_Enroll::statusId_student:
					$stats['Student']++;
					break;

				default:
					break;
			}
		}

		return $stats;
	}


	/**
	 * Checks the enrollment for a course to see if it can be closed out
	 * @author Christiaan van Woudenberg
	 * @version August 1, 2006
	 *
	 * @return string
	 */
  /**
   * @todo-jon-low-small - Make this a property of A25_EnrollmentStatus
   */
	public function checkEnrollment()
	{
		$validEnroll = array(
				A25_Record_Enroll::statusId_completed,
				A25_Record_Enroll::statusId_canceled,
        A25_Record_Enroll::statusId_kickedOut,
				A25_Record_Enroll::statusId_noShow,
				A25_Record_Enroll::statusId_pending,
				A25_Record_Enroll::statusId_failed);
		// Completed, Cancelled, No Show, Pending or Failed

		$canClose = true;
		foreach ($this->Enrollments as $enrollment) {
			if (!in_array($enrollment->status_id, $validEnroll)) {
				$canClose = false;
			}
		}
		return $canClose;
	}


	/**
	 * Show a table with student enrollment information
	 * @author Christiaan van Woudenberg
	 * @version July 23, 2006
	 *
	 * @param boolean $form Should we show form controls?
	 * @return void
	 */
	function showEnrollment( $form )
	{
		$validCourse = array(1,2); // Open or Special

		// Disable form controls if course has already been paid, or is not of open or special status.
		if (($form && $this->is_paid) || ($form && !in_array($this->status_id, $validCourse))) {
			$form = false;
		}

		$str = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="striped"><tbody>';
		if (!count($this->Enrollments)) {
			$str .= '<tbody><tr><td>No students are enrolled for this course.</td></tr></tbody>';
		} else {
      $str .= '<thead><tr><th colspan="9">Active Enrollments</th></tr>';
			$str .= '<tr><td>Student ID</td><td>Status';
			if ($form) {
				$str .= ' <small>(<a href="javascript:void(0);" onClick="markAll(\'complete\',2); markAll(\'pending\',6);">mark students complete</a>)</small>';
			}
			$str .= '</td><td>Student Name</td><td width="5%" align="center">Email</td>'
                    . $this->fireExtraShowEnrollmentHeader()
                    . '<td width="5%" align="center">Cert.</td><td width="5%" align="center">Pay</td></tr></thead>';

			$str .= '<tbody>';
			foreach ($this->alphabeticalEnrollmentsOccupyingSeats() as $enrollment) {
        $str .= $this->fillEnrollment($form, $enrollment);
			}
      $not_occupying_seats = $this->alphabeticalEnrollmentsNotOccupyingSeats();
      if ($not_occupying_seats->count() > 0) {
        $str .= '<tr><th colspan="6">Canceled Enrollments</th></tr>';
        $str .= '<tr class="column_header"><td>Student ID</td><td>Status</td><td>Student Name</td><td width="5%" align="center">Email</td>';
        $str .= $this->fireExtraShowEnrollmentHeader();
        $str .= '<td width="5%" align="center">Cert.</td><td width="5%" align="center">Pay</td></tr>';
        foreach ($not_occupying_seats as $enrollment) {
          $str .= $this->fillEnrollment($form, $enrollment);
        }
      }
			$str .= '</tbody>';

			if ($form) {
				if ($this->checkEnrollment()) {
					$str .= '<tfoot><tr><td colspan="6">Comments';
          $str = $this->addCommentsTooltip($str);
          $str .= '</td></tr>'
              . '<tr><td colspan="6">'
              . '<div style="color: #444; font-weight: normal; font-style: italic;">'
              . A25_DI::PlatformConfig()->courseCommentsDescription()
              . '</div><textarea name="comments" rows="4" cols="50"></textarea>'
              . '<br /></td></tr><tr><td></td><td colspan="5" nowrap="nowrap">';
					if ($this->instructor_2_id > 0) {
						$str .= 'Payroll Split: ';
						$split = array();
						$selectValue = $this->Instructor->getMultipleFee() . '|' . $this->Instructor2->getMultipleFee();
						$selectOption = '$' . $this->Instructor->getMultipleFee()
						. ' / $' . $this->Instructor2->getMultipleFee();
						$split[] = mosHTML::makeOption($selectValue,$selectOption);
						$split[] = mosHTML::makeOption('180|180','$180 / $180');
						$split[] = mosHTML::makeOption('200|160','$200 / $160');
						$str .= mosHTML::selectList( $split, 'split', 'class="inputbox"', 'value', 'text', $selectValue);
					}
					$str .= '<input type="hidden" name="close_course" value="1" /><input type="submit" class="button" value="Submit Course For Payment" onclick="this.disabled=true;this.value=\'Submitting...\'; this.form.submit();"/></td></tr></tfoot>';
				} else {
					$str .= '<tfoot><tr><td></td><td colspan="5"><input type="submit" class="button" value="Update Enrollment Status"/><div class="required">Course can only be submitted for payment once all students are marked Complete, Cancelled, No Show, Pending, Failed or Reservation Expired.</div></td></tr></tfoot>';
				}
			} elseif ($this->is_paid) {
				$str .= '<tfoot><tr><td></td><td colspan="5">No further adjustments may be made to course enrollment; course has been paid.</td></tr></tfoot>';
			}
		}
		$str .= '</table>';
		return $str;
	}

  private function addCommentsTooltip($str)
  {
    if (A25_DI::User()->isAdminOrHigher())
    {
      $head = A25_DI::HtmlHead();
      $tooltip = new A25_Include_Tooltip();
      $tooltip->load();

      // Modify CSS
      $head->append('<style type="text/css">
  #tooltip
  {
    text-align: left;
    color: #ddd;
    background: #222;
  }
  #tooltip:after /* triangle decoration */
  {
    border-top: 10px solid #222;
  }
  #tooltip.top:after
  {
    border-bottom: 10px solid #222;
  }
</style>');

      $str .= ' <a href="javascript:void()" rel="tooltip" title="'
          . '<p style=\'max-width: 200px;\'>This comment field can be used for '
          . 'whatever purposes you would like. If you would like to give '
          . 'guidance to instructors as to what to use this field for, a '
          . 'description can be added.<br /><br />If you would like to set this'
          . ' description, please email Jonathan Albright at jonathan@appdevl.net.'
          . '</p>"><img src="' . A25_Link::to('/images/M_images/con_info.png') . '"/></a>';
    }
    return $str;
  }

    /**
	 * Show a roster table with student enrollment information
	 * @author Garey Hoffman
	 * @version October 18, 2006
	 *
	 * @param boolean $form Should we show form controls?
	 * @return void
	 */
	function showPrintRoster( $form )
	{
		$str = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="striped"><tbody>';
		if (!count($this->Enrollments)) {
			$str .= '<tbody><tr><td>No students are enrolled for this course.</td></tr></tbody>';
		} else {
            $headings = '';
            foreach (A25_DI::PlatformConfig()->courseRosterFields as $field) {
                $headings .= '<td>' . $field . '</td>';
            }
            $headings .= $this->fireExtraShowEnrollmentHeader();
      $str .= '<thead><tr><th colspan="12">Active Enrollments</th></tr>';
			$str .= '<tr>';
            $str .= $headings;
			$str .= '</tr></thead><tbody>';
			foreach ($this->alphabeticalEnrollmentsOccupyingSeats() as $enrollment) {
        $str .= $this->fillPrintRoster($form, $enrollment);
			}
      $str .= '<tr><th colspan="12">Canceled Enrollments</th></tr>';
      $str .= '<tr class="column_header">';
      $str .= $headings;
      $str .= '</tr>';
			foreach ($this->alphabeticalEnrollmentsNotOccupyingSeats() as $enrollment) {
        $str .= $this->fillPrintRoster($form, $enrollment);
			}
			$str .= '</tbody>';
		}
		$str .= '</table>';
		return $str;
	}

	private function alphabeticalEnrollmentsOccupyingSeats()
	{
		$q = Doctrine_Query::create()
			->from('A25_Record_Enroll e')
			->innerJoin('e.Student s')
			->where('e.course_id=?', $this->course_id)
      ->andWhereIn('e.status_id', A25_Record_Enroll::occupiesSeatStatusList())
			->orderBy('s.last_name, s.first_name');
		return $q->execute();
	}
	private function alphabeticalEnrollmentsNotOccupyingSeats()
	{
		$q = Doctrine_Query::create()
			->from('A25_Record_Enroll e')
			->innerJoin('e.Student s')
			->where('e.course_id=?', $this->course_id)
      ->andWhereNotIn('e.status_id', A25_Record_Enroll::occupiesSeatStatusList())
			->orderBy('s.last_name, s.first_name');
		return $q->execute();
	}
  private function fillEnrollment($form, $enrollment)
  {
		$re = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';

		// build list of status
		$cstatus = array();
		$sql = "SELECT `status_id` AS value, `status_name` AS text FROM #__enroll_status;";
		A25_DI::DB()->setQuery($sql);
		$cstatus = A25_DI::DB()->loadObjectList();

    if ($form) {
        switch ($enrollment->status_id) {
            case 1:
                $class = 'markallpending';
                break;

            case 2:
                $class = 'markallcomplete';
                break;

            default:
                $class = '';
                break;
        }
      $statusForm = mosHTML::selectList( $cstatus, 'status[' . $enrollment->xref_id . ']', ' class="' . $class . '"', 'value', 'text', $enrollment->status_id);
    } else {
      $statusForm = $enrollment->Status->status_name;
    }
    $student = $enrollment->Student;
    $studentLink = '<a href="index2.php?option=com_student&task=viewA&id=' . $enrollment->student_id . '">' . $student->firstLastName() . '</a>';
    if (preg_match($re, $student->email)) {
      $emailLink = '<a href="index2.php?option=com_student&task=msgform&id=' . $enrollment->student_id . '" title="Send a message to this student."><img src="' . A25_Link::to('/includes/js/ThemeOffice/messaging.png').'" width="16" height="16" border="0" /></a>';
    } else {
      $emailLink = mosToolTip('This student did not enter a valid e-mail address.  If you need to get a message to this student, please do so by phone.','Warning!',150,'messaging.png','','javascript:void(0)',1) . ' <span class="required" style="font:bold 18px Verdana, sans-serif;">!</span>';
    }
    
    $extraColumn = $this->fireExtraShowEnrollmentColumn($student);

    $certLink = ($enrollment->status_id == 3 || $enrollment->status_id == 2)
        ? '<a class="cert_button" href="' . A25_Link::to('/administrator/components/com_course/'
        . PlatformConfig::certPrinter . '?id=' . $enrollment->xref_id
        . '" title="Print certificate.')
        . '" target="_blank"><img src="'
        . A25_Link::to('/images/M_images/printButton.png')
        . '" width="16" height="16" border="0" /></a>' : '';
    $payLink = ($enrollment->status_id == 1 || $enrollment->status_id == 7) ? '<a href="index2.php?option=com_pay&task=payform&xref_id=' . $enrollment->xref_id . '" title="Process a payment for this enrollment."><img src="' . A25_Link::to('/includes/js/ThemeOffice/dollar.png').'" width="16" height="16" border="0" /></a>' : '';
    $str = '<tr>'
      . '<td>' . $enrollment->student_id . '</td>'
      . '<td>' . $statusForm . '</td>'
      . '<td>' . $studentLink . '</td>'
      . '<td align="center" nowrap="nowrap">' . $emailLink . '</td>'
      . $extraColumn
      . '<td align="center">' . $certLink . '</td>'
      . '<td align="center">' . $payLink . '</td>'
      . '</tr>' . "\n";
    return $str;
  }
  private function fillPrintRoster($form, $enrollment)
  {
		$re = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';

		// build list of status
		$cstatus = array();
		$sql = "SELECT `status_id` AS value, `status_name` AS text FROM #__enroll_status WHERE `status_id` NOT IN (8);";
		A25_DI::DB()->setQuery($sql);
		$cstatus = A25_DI::DB()->loadObjectList();

    // Reason name
    if($enrollment->reason_other == A25_Record_ReasonType::reasonTypeId_Other) {
      $reason_name = $enrollment->reason_other;
    } else {
      $reason_name = $enrollment->ReasonType->reason_name;
    }

    // Status
    if ($form) {
      $class = ($enrollment->status_id == 2) ? 'markallcomplete' : '';
      $statusForm = mosHTML::selectList( $cstatus, 'status[' . $enrollment->xref_id . ']', ' class="' . $class . '"', 'value', 'text', $enrollment->status_id);
    } else {
      $statusForm = $enrollment->Status->status_name;
    }

    // Email link
    $student = $enrollment->Student;
    if (preg_match($re, $student->email)) {
      $emailLink = '<a href="' . A25_Link::to('/administrator/index2.php?option=com_student&task=msgform&id=' . $enrollment->student_id) . '">' . $student->email . '</a>';
    } else {
      $emailLink = mosToolTip('This user does not have a valid e-mail address and will only receive messages if they log on to the site as a student.','Warning!',150,'messaging.png','','index2.php?option=com_student&task=msgform&id=' . $enrollment->student_id,1) . ' <span class="required" style="font:bold 18px Verdana, sans-serif;">!</span>';
    }

    // Pay link
    if($enrollment->status_id == 2 || $enrollment->status_id == 3){
      $paid ="Y";
    }else{
      $paid ="N";
    }

    $str = '<tr>';
    foreach (A25_DI::PlatformConfig()->courseRosterFields as $field) {
        switch ($field) {
            case 'Student ID':
                $str .= '<td>' . $enrollment->student_id . '</td>';
                break;
            case 'Name':
                $str .= '<td>' . $student->first_name . ' ' . $student->last_name . '</td>';
                break;
            case 'Reason For Attending':
                $str .= '<td>' . $reason_name . '</td>';
                break;
            case 'Status':
                $str .= '<td>' . $enrollment->Status->status_name . '</td>';
                break;
            case 'Paid':
                $str .= '<td>' . $paid . '</td>';
                break;
            case 'Email':
                $str .= '<td nowrap="nowrap">' . $emailLink . '</td>';
                break;
            case 'Phone':
                $str .= '<td nowrap="nowrap">' . $student->home_phone . '</td>';
                break;
            case 'Age':
                $str .= '<td>' . $student->age(strtotime($this->course_start_date)) . '</td>';
                break;
            case 'Special Needs':
                $str .= '<td>' . $student->special_needs . '</td>';
                break;
            case 'Driver License #':
                $str .= '<td>' . $student->license_no . '</td>';
                break;
            case 'Date of Birth':
                $str .= '<td>' . $student->date_of_birth . '</td>';
                break;
            default:
                break;
        }
    }
    $str .= $this->fireExtraShowEnrollmentColumn($student);
    $str .= '</tr>' . "\n";
    return $str;
  }
	public function formattedDate( $field, $format='l F j, Y \a\t g:i a' ) {
		if (isset($this->$field) && $this->$field > 0) {
			return date($format, strtotime($this->$field));
		} else {
			return false;
		}
	}
	public function getFormattedDateTime()
	{
		return $this->formattedDate('course_start_date','M d, Y h:i A' );
	}

	public function prettyDateTime()
	{
		return $this->formattedDate('course_start_date','M d') . ' at ' .
				$this->formattedDate('course_start_date','h:i A');
	}
	public function prettierDateTime()
	{
		// Display the year, if the course isn't this year.
		if ($this->course_start_date > (date('Y') + 1) . '-01-01')
			$date = $this->formattedDate('course_start_date','M j, Y');
		else
			$date = $this->formattedDate('course_start_date','M j');


		return $date . ', ' .
				$this->formattedDate('course_start_date','g:i a');
	}
	public function date()
	{
		return $this->formattedDate('course_start_date','Y-m-d');
	}
	public function longDate()
	{
		return $this->formattedDate('course_start_date','l F j, Y');
	}

	/**
     * Sets the course to start at $time, and end 4 hours later.
	 *
	 * @deprecated - not every state has a course length of 4 hours, so we do
	 * not use this for actual production code.  Currently, this is only used
	 * for setting up tests.
     *
     * @param timestamp $datetime
     */
	public function setCourseTime($datetime)
	{
		$this->course_start_date = A25_Functions::formattedDateTime($datetime);
		$this->duration = '4:00';
    }
	public function assignInstructor(A25_Record_User $instructor)
	{
		$this->instructor_id = $instructor->id;
    }
	public function assignLocation(A25_Record_LocationAbstract $location)
	{
		$this->location_id = $location->location_id;
	}
	public function getLocationName()
	{
		if ($this->relatedIsDefined('Location'))
			return $this->Location->location_name;
	}
	public function getCityName()
	{
		if ($this->relatedIsDefined('Location'))
			return $this->Location->city;
	}

	public function isPast()
	{
		return $this->isPastDeadline('now');
	}

	public function isPastPaymentOptionDeadline()
	{
		return $this->isPastDeadline($this->getSetting('register_cc_days') . ' days');
	}

	public function isPastPaymentDeadline()
	{
		return $this->isPastDeadline($this->getSetting('payment_deadline') . ' hours');
	}

	public function isPastEnrollmentDeadline()
	{
		return $this->isPastDeadline($this->getSetting('enrollment_deadline'));
	}

	public function isPastLateFeeDeadline()
	{
		return $this->isPastDeadline($this->getSetting('late_fee_deadline') . ' hours');
	}

	public function isPastCancellationDeadline()
	{
		return $this->isPastDeadline($this->getSetting('cancellation_deadline') . ' hours');
	}

	private function isPastDeadline($deadline)
	{
		if (strtotime($this->course_start_date) < strtotime($deadline))
			return true;
		return false;
	}

	public function paymentsAreFrozen()
	{
		return ($this->isPastPaymentDeadline() && !$this->isPast());
	}

	public function getSeatsTaken()
	{
		$active = array();
		$enrollments = $this->Enrollments;
		foreach ($enrollments as $enrollment)
		{
			if( $enrollment->occupiesSeat() )
				$active[] = $enrollment;
		}
		return $active;
	}
	/**
	 *
	 * @param int $statusId - This should be one of the status ID constants from
	 * A25_Record_Enroll.
	 */
	public function getEnrollmentStatusCount($statusId)
	{
		$enrollments = $this->Enrollments;
		$count = 0;
		foreach ($enrollments as $enroll) {
			if ($enroll->status_id == $statusId) {
				$count++;
			}
		}
		return $count;
	}
	public function getCourtOrderedCount()
	{
		$enrollments = $this->Enrollments;
		$count = 0;
		foreach ($enrollments as $enroll) {
			if ($enroll->isLegalMatter()) {
				$count++;
			}
		}
		return $count;
	}
	public function getNotCourtOrderedCount()
	{
		$enrollments = $this->Enrollments;
		$count = 0;
		foreach ($enrollments as $enroll) {
			if (!$enroll->isLegalMatter()) {
				$count++;
			}
		}
		return $count;
	}
	public function getPaidEnrollmentCount()
	{
		$enrollments = $this->Enrollments;
		$count = 0;
		foreach ($enrollments as $enroll) {
			if ($enroll->isPaid() && in_array($enroll->status_id,
          A25_Record_Enroll::canCountAsPaidStatusList())) {
				$count++;
			}
		}
		return $count;
	}

	/**
	 * Public for testing only.
	 *
	 * @return array of A25_Record_Payment
	 */
	public function getPayments()
	{
		$q = Doctrine_Query::create()
			->select('p.*')
			->from('A25_Record_Pay p')
			->innerJoin('p.Order o')
			->innerJoin('o.Enrollment e')
			->where(A25_Record_Enroll::active('e'))
			->andWhere('p.refund_type_id IS NULL OR p.refund_type_id = ?',
					A25_Record_OrderItemType::typeId_CourseFee)
			->andWhere('e.course_id = ?', $this->course_id);
		return $q->execute();
	}

	public function getProfit()
	{
		return $this->getGrossRevenue() - $this->getInstructorFees();
	}

	public function getInstructorFees()
	{
		$return = 0;
		if ($this->status_id != self::statusId_Cancelled) {
			if ($this->relatedIsDefined('Instructor'))
				$return += $this->Instructor->single_fee;
			if ($this->relatedIsDefined('Instructor2'))
				$return += $this->Instructor2->multiple_fee;
		}
		return $return;
	}

	/**
	 * This function calculates the gross revenue for a course, based on the
	 * order items of paid orders for active enrollments in the course.  We do
	 * not subtract Money Order Discounts yet, although we could if we need to.
	 * At the time we first wrote this, it was decided that it wasn't important
	 * to do so.  If we did do it, we would have to make sure that money order
	 * discounts get transferred when students re-enroll. - Thomas, 6/9/10
	 */
	public function getGrossRevenue()
	{
        $db = A25_DI::DB();
        $q = self::grossRevenueQuery(' WHERE e.course_id = '
            . $this->course_id);
        $db->setQuery($q);

        $total = $db->loadObjectList();
        $gross_revenue = $total[0]->gross_revenue;

        if ($gross_revenue)
            return $gross_revenue;

		return 0;
	}

    public static function grossRevenueQuery($where)
    {
        $strategy = new \AppDevl\QueryStrategy\MysqlStringStrategy(
            \Acre\A25\Query\CourseJoiner::doctrineToSqlTranslations());
        $joiner = new \Acre\A25\Query\CourseJoiner($strategy);
        $gross_revenue_query = new Acre\A25\Query\CourseGrossRevenueQuery(
            $joiner, $strategy);
        $query = $gross_revenue_query->select('');
        $query = $gross_revenue_query->from($query);
        $query .= $where;
        $query = $gross_revenue_query->groupBy($query);
        return $query;
    }

	public function getNumberOfInstructors()
	{
		if( $this->instructor_2_id > 0 )
			return 2;
		else if ( $this->instructor_id > 0 )
			return 1;
		else
			return 0;
	}

	/**
	 * @return string
	 */
	public function typeName()
	{
		if ($this->relatedIsDefined('Type'))
			return $this->Type->getName();
	}
	/**
	 * @return string
	 */
	public function instructorName()
	{
		if ($this->relatedIsDefined('Instructor'))
			return $this->Instructor->name;
	}
	/**
	 * @return string
	 */
	public function instructor2Name()
	{
		if ($this->relatedIsDefined('Instructor2'))
			return $this->Instructor2->name;
	}
	/**
	 * Returns the name of the current course status.
	 * @return string
	 */
	public function getStatus()
	{
		switch ($this->status_id) {
			case self::statusId_Open:
				return 'Open';
			case self::statusId_Closed:
				return 'Closed';
			case self::statusId_Cancelled:
				return 'Canceled';
			default:
				return '';
		}
	}
	public function getLateFee()
	{
		return $this->getSetting('late_fee');
	}
  // Strips the decimals from the late fee if it ends in '.00'
  public function getLateFeeWithoutDecimals()
  {
    return preg_replace('/\.00/', '', $this->getLateFee());
  }
  public function lateFeeFootnote()
  {
    return '* A late fee of $' . $this->getLateFeeWithoutDecimals()
      . ' applies to any payment that occurs within '
      . $this->getSetting('late_fee_deadline')
      . ' hours of the course or later.';
  }

	public function emailStudents($subject, $body)
	{
		$q = Doctrine_Query::create()
			->select('e.student_id')
			->from('A25_Record_Enroll e')
			->where('e.course_id=?', $this->course_id)
			->andWhere(A25_Record_Enroll::active('e'));

		$enrollments = $q->execute();

		foreach ($enrollments as $enroll) {
			$message = new A25_Record_StudentMessage();

			$message->student_id = $enroll->student_id;
			$message->course_id = $this->course_id;
			$message->subject = $subject;
			$message->message = $body;

			if (!$message->check()) {
				throw new A25_Exception_DataConstraint($message->getError());
			}
			if (!$message->send()) {
				throw new A25_Exception_DataConstraint($message->getError());
			}
		}
        
		return count($enrollments);
	}

	/**
	 * Cancels regardless of current enrollment status
	 */
	public function cancelAllEnrollments()
	{
		foreach($this->Enrollments as $enrollment) {
			$enrollment->cancel();
		}
	}

	public static $overridableSettings = array('fee', 'late_fee',
		'cancellation_deadline', 'enrollment_deadline', 'late_fee_deadline',
		'payment_deadline', 'enrollment_email_body',
		'course_completed_email_body', 'payment_reminder_email_body',
		'register_cc_days');

	public function getSetting($fieldName)
	{
		if (!in_array($fieldName, self::$overridableSettings))
			throw new Exception("Attempted to grab invalid overridable setting");

		$detector = new A25_SettingsDetector();
		return $detector->findLeafSetting($this, $fieldName);
	}
	public function settingParent()
	{
		if ($this->relatedIsDefined('Location'))
			return $this->Location;
		 else
			return null;
	}
	public function hasOverriddenSettings()
	{
		foreach (self::$overridableSettings as $setting_field)
			if ($this->$setting_field != null)
				return true;

		return false;
	}
	public function getCourseCompletedEmailBody($reason_id = null)
	{
		$body = $this->getSetting('course_completed_email_body');
		$body = A25_StringReplace::secureUrl($body);
		$body = A25_StringReplace::completionInfo($body, $reason_id);
		return $body;
    }
	public function getEnrollmentEmailBody()
	{
		$body = $this->getSetting('enrollment_email_body');
		$body = A25_StringReplace::secureUrl($body);
		return $body;
    }
	public function openSeats()
	{
		$count = $this->course_capacity;

		foreach ($this->Enrollments as $enroll)
			if ($enroll->occupiesSeat())
				$count--;

		return $count;
	}
	public function timeInfoInTableRow()
	{
		$return = '<tr>'
			. '<td class="formlabel">';
        $return .= $this->printCourseTime();
        $return .= ':</td>'
			. '<td>' . $this->timeInfoHtml() . '</td>'
			. '</tr>';
        return $return;
	}
	public function timeInfoHtml()
	{
		return $this->longDate() . ', ' . $this->startsAt()
			. ' &#8211; ' . $this->endsAt();
	}
	public function endsAt()
	{
		$time = strtotime($this->course_start_date);
		$seconds = A25_Functions::durationToSeconds($this->duration);
		$time = strtotime("+$seconds seconds", $time);
		return date('g:i a', $time);
	}
	public function startsAt()
	{
		return $this->formattedDate('course_start_date', 'g:i a');
	}
    
    function fireExtraShowEnrollmentHeader()
    {
        foreach (A25_ListenerManager::all() as $listener)
        {
            if ($listener instanceof A25_ListenerI_CourseEnrollmentColumn) {
                return $listener->extraShowEnrollmentHeader();
            }
        }
    }
    
    function fireExtraShowEnrollmentColumn($student)
    {
        foreach (A25_ListenerManager::all() as $listener)
        {
            if ($listener instanceof A25_ListenerI_CourseEnrollmentColumn) {
                return $listener->extraShowEnrollmentColumn($student);
            }
        }
    }
}
