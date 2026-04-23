<?php
/**
 * @deprecated - Use Zend_Form_Element_Select with a Doctrine statement instead.
 * See A25_Filter_ReasonForEnrollment for an example.
 */
class A25_Default_MethodObject_GenerateReasonList
{
	protected $_location;
	
	/**
	 * Although $_student and $_course are not used in this file, they are used
	 * by subclasses, so be hesitant to remove them.
	 * 
	 * @var type 
	 */
	protected $_student;
	protected $_course;

	public function __construct($location, A25_Record_Student $student,
			A25_Record_Course $course = null)
	{
		$this->_location = $location;
		$this->_student = $student;
		$this->_course = $course;
	}
	
	public function reasonList($isAdmin)
	{
		require_once(ServerConfig::webRoot . '/includes/joomlaClasses.php');

		$reasons = $this->reasonListQuery($isAdmin)->execute();

		$reasonList = array();
		$reasonList[] = mosHTML::makeOption('','- Select One -');
		foreach ($reasons as $reason) {
			$reasonList[] = mosHTML::makeOption($reason->reason_id,
					$reason->reason_name);
		}

		return mosHTML::selectList( $reasonList, 'reason_id',
			' class="inputbox" style="max-width: 100%;" tmt:invalidindex="0" tmt:message="Please select your reason for attending." onChange="checkReason(this); checkPRCode(this)"',
			'value', 'text', null);
	}

	protected function reasonListQuery($isAdmin)
	{
		$parent_locations = $this->_location->parentLocationIds();
        if (A25_DI::PlatformConfig()->forbidFrontEndCourtEnrollments && $isAdmin == false) {
		$q = Doctrine_Query::create()
				->from('A25_Record_ReasonType r')
				->whereIn('r.location_id', $parent_locations)
				// Ticket #256 requested the removal of 'other' as an option.
				->andWhere('r.reason_key <> ?', 'other')
                ->andWhere('r.reason_key <> ?', 'Court Order')
                ->andWhere('r.reason_key <> ?', 'Pending Legal Matter')
                ->andWhere('r.reason_key <> ?', 'Court Diversion')
        ->andWhere('r.active = 1')
				->orderBy('r.location_id, r.reason_id');
        }
        else {
		$q = Doctrine_Query::create()
				->from('A25_Record_ReasonType r')
				->whereIn('r.location_id', $parent_locations)
				// Ticket #256 requested the removal of 'other' as an option.
				->andWhere('r.reason_key <> ?', 'other')
        ->andWhere('r.active = 1')
				->orderBy('r.location_id, r.reason_id');
        }

		return $q;
	}
}