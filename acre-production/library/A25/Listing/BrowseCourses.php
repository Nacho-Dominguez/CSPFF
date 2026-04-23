<?php

class A25_Listing_BrowseCourses extends A25_Listing
{
	private $radius;
	private $zip;
    private $spanishOnly;
  protected $item_name = 'courses';

	public function __construct($zip = null, $radius = 15, $offset = 0, $spanishOnly = 0)
	{
		parent::__construct(20, $offset);

		if ($radius < 1)
			$radius = 25;

		$this->radius = $radius;
		$this->zip = $zip;
        $this->spanishOnly = $spanishOnly;

		A25_DI::HtmlHead()->stylesheet('/templates/aliveat25/css/a25_browsecourses.css');
		A25_DI::HtmlHead()->stylesheet('/templates/aliveat25/css/blue_links.css');
	}

	protected function formatRow(A25_DoctrineRecord $course)
	{
        $return = array();
        if (A25_DI::PlatformConfig()->courseIsOnline == false) {
            $return['Date/Time'] = '<div style="min-width: 60px;">' . $course->prettierDateTime() . '</div>';
        }
        $return['Location'] = $this->locationColumn($course);
        $return['Action'] = '<div style="max-width: 310px; float: right;">' . $this->actionColumn($course) . '</div>';
        return $return;
	}

	protected function actionColumn(A25_Record_Course $course)
	{
        if ($course->status_id == A25_Record_Course::statusId_Cancelled)
            $return = '<div class="no_enroll">Class Canceled</div>';
		else if ($course->isPastEnrollmentDeadline())
			$return = '<div class="no_enroll">Registration Closed</div>';
		else if ($course->openSeats() < 1)
			$return = '<div class="no_enroll">Class is Full</div>';
        else {
            $return = $this->seatsAndEnroll($course);
            if (A25_DI::PlatformConfig()->showWhenRegistrationCloses) {
                $return .= '<div style="font-size: 10px; color: #770000;">Registration closes in ' . 
                        $this->timeUntilEnrollmentDeadline($course) . '</div>';
            }
            if ($course->isPastLateFeeDeadline())
                $return .= '<div class="late_fee">(Since class is within '
                . $course->getSetting('late_fee_deadline')
                . ' hours, there is a late registration fee of $'
                . $course->getLateFeeWithoutDecimals() . ')</div>';
        }
    
		$types = new Config_CourseTypes();
		$return .= $types->actionColumn($course);
        $return .= self::fireActionColumn($course);

		return $return;
	}
    
    private function timeUntilEnrollmentDeadline($course) {
        $enrollment_deadline = strtotime($course->course_start_date) - strtotime($course->getSetting('enrollment_deadline'));
        $timezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $days = intval(($enrollment_deadline) / 86400);
        if ($days > 1) {
            $return = $days . ' days';
        } elseif ($days == 1) {
            $return = '1 day';
        } else {
            $return = intval(($enrollment_deadline) / 3600) . ' hours';
        }
        date_default_timezone_set($timezone);
        return $return;
    }

	protected function seatsAndEnroll(A25_Record_Course $course)
	{
        $return = '';
        if (A25_DI::PlatformConfig()->courseIsOnline == false) {
            $return .= '<div class="seats_left">' . $this->openSeats($course);
        }
		$return .= '</div><div class="enroll">' . $this->enrollLink($course)
				. '</div>';
        return $return;
	}

	protected function openSeats(A25_Record_Course $course)
	{
		return $course->openSeats() . ' seats left';
	}

	protected function enrollLink(A25_Record_Course $course)
	{
		$link = $this->fireEnrollLink($course);
		if (!$link)
			$link = A25_Link::withoutSef("/course-info?course_id=$course->course_id");
		$return = '<a class="enroll_link" rel="nofollow" href="'
				. $link
				. '">Enroll</a>';
    if (!A25_DI::PlatformConfig()->acceptOnlyCreditCards &&
        $course->isPastPaymentOptionDeadline() && PlatformConfig::defaultCourtFee > 0)
      $return .= ' <span class="asterisk">*</span>';

		return $return;
	}

	protected function locationColumn(A25_Record_Course $course)
	{
		$locationInfo = '<a href="'
			. A25_Link::withoutSef("/course-info?course_id=$course->course_id")
			. '">' . $course->getLocationName() . '</a>, '
					. $course->getCityName();

		if ($this->zip && $course->relatedIsDefined('Location'))
			$locationInfo .= '<br/><span style="font-size: 10px; color: #337733">'
					. A25_OldCom_Student_CourseBrowseHtml::distance(
					$this->zip,$course->Location->zip) . " away</span>";

		return $locationInfo;
	}

	protected function query()
	{
        $statuses = A25_Record_Course::$activeStatuses;
        if (A25_DI::PlatformConfig()->showCanceledCoursesToPublic) {
            $statuses[] = A25_Record_Course::statusId_Cancelled;
        }
		$q = Doctrine_Query::create()
				->from('A25_Record_Course c')
				->innerJoin('c.Location l')
				->leftJoin('l.ZipCode z')
				->leftJoin('c.Enrollments e')
				->where('c.course_start_date >= ?', date('Y-m-d') . ' 00:00:00')
				->andWhereIn('c.status_id', $statuses)
				->andWhere('c.published = ?', 1)
				->orderBy('c.course_start_date');
        
        if ($this->spanishOnly) {
            $q->andWhere('c.course_type_id = ?', A25_Record_Course::typeId_Spanish);
        }

		if ($this->zip) {
			$nearby = false;
			$sql = "SELECT * FROM #__zip_codes WHERE `zip_code`='$this->zip'
					AND ABS(`latitude`)>0 AND ABS(`longitude`)>0";
			A25_DI::DB()->setQuery($sql);
			$curr = null;
			A25_DI::DB()->loadObject($curr);

			if (!isset($curr->latitude)) {
				$sql = "SELECT * FROM #__zip_codes WHERE `zip_code` > "
					 . ((int) $this->zip-PlatformConfig::zipSearchLimit)
					 . " AND `zip_code` < " . ((int) $this->zip+PlatformConfig::zipSearchLimit)
					 . " AND ABS(`latitude`)>0 AND ABS(`longitude`)>0 ORDER BY
						ABS(`zip_code`-" . (int) $this->zip . ") LIMIT 1";
				A25_DI::DB()->setQuery($sql);
				$curr = null;
				A25_DI::DB()->loadObject($curr);

				// Could not find a zip within the current search criterion.
				if (!isset($curr->latitude)) {
					A25_DI::Redirector()->redirect( 'index.php?option=com_course', "Could not locate zip code $this->zip." );
				} else {
					$nearby = true;
				}
			}

			$latrange = $this->radius / ((6076 / 5280) * 60);
			$longrange = $this->radius / (((cos($curr->latitude * 3.141592653589 / 180) * 6076) / 5280) * 60);

			$q->andWhere('z.latitude < ?', (float) ($curr->latitude + $latrange));
			$q->andWhere('z.latitude > ?', (float) ($curr->latitude - $latrange));
			$q->andWhere('z.longitude < ?', (float) ($curr->longitude + $longrange));
			$q->andWhere('z.longitude > ?', (float) ($curr->longitude - $longrange));
            
        // Show virtual courses to everyone
            $q->orWhere('c.course_start_date >= ?', date('Y-m-d') . ' 00:00:00')
                    ->andWhereIn('c.status_id', $statuses)
                    ->andWhere('c.published = ?', 1)
                    ->andWhere('l.virtual = ?', 1);
		}

		return $q;
	}

  /**
    * @todo-soon - remove duplication with A25_Listing_Courses->displayNavStatus() 
    */
	protected function heading()
	{
		$this->headingTop();
    $this->displayNavStatus();
		parent::heading();
	}

	protected function headingTop()
	{
		?>
<form name="filter" id="filter" action="find-a-course" method="get">
    <?php if (!A25_DI::PlatformConfig()->allClassesVirtual) { ?>
<div style="float: right;">
	Search for classes within <?php echo $this->radiusDropdown(); ?> miles of zip:
	<input type="text" class="inputbox" name="zip" size="5" maxlength="5"
	value="<?php echo $this->zip; ?>" />
	<input type="submit" value="Go" />
</div>
    <?php } ?>
</form>
		<?php
	}

	protected function grid($rows)
	{
		$grid = new A25_HeadlessGrid($rows);
		$grid->setColumnCss('Action', 'text-align: right;');
		return $grid;
	}
	private function radiusDropdown()
	{
		$radii = array();
		foreach (self::searchRadiusArray() as $r)
			$radii[] = mosHTML::makeOption($r);

		$selected = $this->radius;
		if (!$selected)
			$selected = 15;

		return mosHTML::selectList( $radii, 'radius', null, 'value', 'text',
				$selected);
	}
	private static function searchRadiusArray()
	{
		$searchRadii = array(5,15,25,50,100);
		if (!in_array(PlatformConfig::defaultSearchRadius,$searchRadii)) {
			$searchRadii[] = PlatformConfig::defaultSearchRadius;
		}
		sort($searchRadii);

		return $searchRadii;
	}
	protected static function fireEnrollLink(A25_Record_Course $course)
	{
		$link = false;
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_BrowseCourses) {
				$link = $listener->enrollLink($course);
			}
		}
		return $link;
	}
    private static function fireActionColumn($course)
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_ActionColumn) {
                return $listener->actionColumn($course);
            }
        }
    }
}
