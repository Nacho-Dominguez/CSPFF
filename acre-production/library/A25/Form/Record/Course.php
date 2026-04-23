<?php

/**
 * This form uses a table layout instead of Zend_Form's standard definition list
 * layout.  While we often want forms to look like tables (such as when using a
 * desktop computer), this was a mistake.  We are better off using definition
 * lists, or even better, <label> & <fieldset>, with CSS making it look like a
 * table.
 * 
 * There are 2 main reasons that these would be better ways to create it:
 * 1. The semantic description of the data is cleaner in the code, and more
 *    friendly to search engines.
 * 2. We need flexible layouts so that the forms still work on mobile devices,
 *    as suggest by Responsive Design.
 * 
 * See this discussion for further thoughts:
 * http://stackoverflow.com/questions/519234/why-use-definition-lists-dl-dd-dt-tags-for-html-forms-instead-of-tables
 * 
 * As with all forms, it may be helpful to visit
 * https://github.com/thomasalbright/acre/wiki/forms
 */
class A25_Form_Record_Course extends A25_Form_Record
{	
	private $_location;
	
	public $overridableSettings = array('fee','late_fee','cancellation_deadline',
			'enrollment_deadline','late_fee_deadline','payment_deadline');

	public function __construct(A25_Record_Course $course, $returnUrl,
			$isReadOnly = false)
    {
		$this->_isReadOnly = $isReadOnly;
		
		$this->addPrefixPath('A25_Form_Decorator', 'A25/Form/Decorator/', 'decorator');
		
		$this->setDecorators(array(
			'FormElements',
			array('HtmlTag', array('tag' => 'table')),
			'Form',
		));
		
		$htmlHead = A25_DI::HtmlHead();
		
		$htmlHead->append('
		<style type="text/css" media="all">
			#ActiveRecordForm td {
				text-align: left;
			}
		</style>');
		
		if (!$isReadOnly) {
			$htmlHead->includeJquery();
			
			// Setup for timePicker javascript
			$htmlHead->stylesheet(
					'/includes/third-party/timePicker/timePicker.css');
			$htmlHead->append('
			<style type="text/css" media="all">
				div.time-picker-12hours {
					width:88px;
				}
			</style>');
			$htmlHead->javascriptFile(
					'/includes/third-party/timePicker/jquery.timePicker.min.js');
			
			$duration = $course->duration ? (A25_Functions::durationToSeconds($course->duration) / 3600) : 0;
			
			$htmlHead->append('
			<script type="text/javascript">
			jQuery(function() {
				$("#end_time").after(" (<span id=\'duration\'>'
					. $duration . '</span> hours)");

				$("#start_time").timePicker({
					startTime: "06:00", // Using string. Can take string or Date object.
					endTime: "18:00",
					show24Hours: false,
					separator:":",
					step: 30
				});
				
				$("#end_time").timePicker({
					startTime: "06:00",
					show24Hours: false,
					separator:":",
					step: 30
				});
			

				// An example how the two helper functions can be used to achieve 
				// advanced functionality.
				// - Linking: When changing the first input the second input is updated and the
				//   duration is kept.

				// Store time used by duration.
				var oldTime = $.timePicker("#start_time").getTime();

				// Keep the duration between the two inputs.
				$("#start_time").change(function() {
					var duration = 1000*'
					. A25_Functions::durationToSeconds(PlatformConfig::courseDuration)
					. ';
					if ($("#end_time").val()) {
						// Keep duration that was already in place
						duration = ($.timePicker("#end_time").getTime() - oldTime);
					}
					var time = $.timePicker("#start_time").getTime();
					// Calculate and update the time in the second input.
					$.timePicker("#end_time").setTime(new Date(new Date(time.getTime() + duration)));
					oldTime = time;
					
					$("#duration").html((duration/3600000));
				});
				// Validate.
				$("#end_time").change(function() {
				  var duration = ($.timePicker("#end_time").getTime() - $.timePicker("#start_time").getTime());
				  $("#duration").html((duration/3600000));
				});
			});
			</script>');
			
			
			// Setup for calendar javascript
			$htmlHead->stylesheet(
					'/includes/third-party/jquery-ui-1.8.16.custom/css/jquery-ui-1.8.16.custom.css');
			$htmlHead->javascriptFile('/includes/third-party/jquery-ui-1.8.16.custom/jquery-ui-1.8.16.custom.min.js');
			$htmlHead->append('
			<script type="text/javascript">
			jQuery(function() {
				$("#date").datepicker();
			});
			</script>');
			
			$this->addJavascriptForLocationAjax();
		} else {
			// We don't want to designate required fields when in read-only mode
			$htmlHead->append('
			<style type="text/css" media="all">
				label.required {
					color: black;
				}
			</style>');
		}

		$this->successMessage = 'Course Saved';

		if($isReadOnly) {
			$course_id = new A25_Form_Element_Text('course_id');
			$this->addElement($course_id);
		}

		$course_type = new A25_Form_Element_Select_FromTable('course_type_id',
				'jos_course_type','type_id','type_name');
        $course_type->setRequired(true)
				->setLabel('Course Type');
		$this->addElement($course_type);
		
		if (A25_DI::User()->isAdminOrHigher()) {
			$published = new A25_Form_Element_Checkbox('published');
			$published->setLabel('Published');
			$this->addElement($published);
		}

		$location_id = new A25_Form_Element_Select_Location('location_id',
				A25_DI::User());
        $location_id->setRequired(true)
				->setLabel('Location');
		$this->addElement($location_id);

		// @todo-soon - The direct use of $_POST is fragile and could result in
		// a bug that is difficult to detect.  Write an automated test to catch
		// any potential problems.
    if ($_POST['location_id'])
			$selected_location_id = intval($_POST[$location_id->getName()]);
		else
			$selected_location_id = $course->location_id;
		
		if ($selected_location_id)
			$location = A25_Record_Location::retrieve($selected_location_id);
		
		$this->createInstructor1Field($location, $isReadOnly,
				$course);

		$instructor_2_id = new A25_Form_Element_Select_Instructor(
				'instructor_2_id', $location);
		$instructor_2_id = $this->appendCurrentInstructorIfNecessary(
				$instructor_2_id, $course->instructor_2_id);
		$instructor_2_id->setRequired(false);
		
		$this->addElement($instructor_2_id);
		$instructor_2_id->setDecorators(array(
				'ViewHelper',
				'Errors',
		));
		
		
		$this->addDisplayGroup(
			array('instructor_id', 'instructor_2_id'),
			'instructors',
			array(
				'legend' => 'Instructor(s)',
				'decorators' => array(
					'FormElements',
					/**
					 * In order for this display group to be formatted in a
					 * table row, like all of the other fields in this table, we
					 * replace the 'Fieldset' View Helper with
					 * A25_FormView_Fieldset.
					 */
					'Fieldset',
				)
			)
		);
		
		$date = new A25_Form_Element_Text('date');
		$date->addValidator('date', false, array('format' => 'MM/DD/YYYY'));
		$date->setRequired(true)->setLabel('Start Date');
		$date->setAttrib('size', 10);
		$date->setAttrib('maxlength', 10);
		if (PlatformConfig::instructorClassCreationDeadline > 0)
			$date->addValidator(new A25_Form_Validate_InstructorCourseStartDate(
					$course->date));
		$this->addElement($date);
		$date->setDecorators(array('ViewHelper', 'Errors'));
		
		$start_time = new A25_Form_Element_Text('start_time');
		$start_time->setAttrib('size', 8);
		$start_time->setAttrib('maxlength', 8);
		$start_time->addValidator('date', false, array('format' => 'h:m a'));
		$start_time->setRequired(true)->setLabel('Start Time');
		$start_time->setLabel('from');
		$this->addElement($start_time);
		$start_time->setDecorators(array(
				'ViewHelper',
				'Errors',
				array('Label', array('tag' => 'span'))
		));

		$end_time = new A25_Form_Element_Text('end_time');
		$end_time->setAttrib('size', 8);
		$end_time->setAttrib('maxlength', 8);
		$end_time->addValidator('date', false, array('format' => 'h:m a'));
		$end_time->setRequired(true)->setLabel('to');
		$this->addElement($end_time);
		$end_time->setDecorators(array(
				'ViewHelper',
				'Errors',
				array('Label', array('tag' => 'span'))
		));

		$this->addDisplayGroup(
			array('date','start_time','end_time'),
			'when',
			array(
				'legend' => 'When',
				'decorators' => array(
					'FormElements',
					/**
					 * In order for this display group to be formatted in a
					 * table row, like all of the other fields in this table, we
					 * replace the 'Fieldset' View Helper with
					 * A25_FormView_Fieldset.
					 */
					'Fieldset',
				)
			)
		);

		$course_capacity = new A25_Form_Element_Text('course_capacity');
		$course_capacity->setRequired(true);
		$course_capacity->setAttrib('size', 3);
		$course_capacity->setAttrib('maxlength', 8);
		$this->addElement($course_capacity);
		$this->limitCourseCapacityForInstructors($course_capacity);

		$course_description = new A25_Form_Element_Textarea('course_description');
		$course_description->setRequired(false)
				->setLabel('Notes for Students')
				->setAttrib('rows', 6)
				->setAttrib('cols', 60);
		if(!$isReadOnly)
				$course_description->setDescription('Enter information about this specific course date. Example: Room #, Food & Drinks Provided.');
		$this->addElement($course_description);

		$zoom_link = new A25_Form_Element_Textarea('zoom_link');
		$zoom_link->setRequired(false)
				->setLabel('Zoom Link')
				->setAttrib('rows', 1)
				->setAttrib('cols', 60);
		if(!$isReadOnly) {
				$zoom_link->setDescription('For virtual courses, the meeting url can be provided for inclusion in reminder emails.');
        }
		$this->addElement($zoom_link);
    
    $this->fireDuringEditCourseForm();

		if (A25_DI::User()->isAdminOrHigher()) {

			// Fields inherited from location
			$fee = new A25_Form_Element_Text('fee');
			$fee->setRequired(false);
			$fee->setAttrib('size', 4);
			$fee->setAttrib('maxlength', 5);
			$fee->setLabel('Tuition for Non-Court-Ordered');
			$this->addElement($fee);

			$late_fee = new A25_Form_Element_Text('late_fee');
			$late_fee->setRequired(false);
			$late_fee->setAttrib('size', 4);
			$late_fee->setAttrib('maxlength', 5);
			$late_fee->setLabel('Late Payment Fee');
			$this->addElement($late_fee);

			$cancellation_deadline = new A25_Form_Element_Text('cancellation_deadline');
			$cancellation_deadline->setRequired(false);
			$cancellation_deadline->setAttrib('size', 2);
			$cancellation_deadline->setAttrib('maxlength', 2);
			$cancellation_deadline->setLabel('Cancellation Deadline');
			$this->addElement($cancellation_deadline);

			$enrollment_deadline = new A25_Form_Element_Text('enrollment_deadline');
			$enrollment_deadline->setRequired(false);
			$enrollment_deadline->setAttrib('size', 20);
			$enrollment_deadline->setAttrib('maxlength', 25);
			$enrollment_deadline->setLabel('Enrollment Deadline');
			$this->addElement($enrollment_deadline);

			$late_fee_deadline = new A25_Form_Element_Text('late_fee_deadline');
			$late_fee_deadline->setRequired(false);
			$late_fee_deadline->setAttrib('size', 2);
			$late_fee_deadline->setAttrib('maxlength', 2);
			$late_fee_deadline->setLabel('Late Fee Deadline');
			$this->addElement($late_fee_deadline);

			$payment_deadline = new A25_Form_Element_Text('payment_deadline');
			$payment_deadline->setRequired(false);
			$payment_deadline->setAttrib('size', 2);
			$payment_deadline->setAttrib('maxlength', 2);
			$payment_deadline->setLabel('Payment Deadline');
			$this->addElement($payment_deadline);

			$this->_location = $course->settingParent();
			if (!$this->_location) {
				$this->_location = new A25_Record_Location();
			}
				
			$this->formatInheritedSetting($fee, 'dollars');
			$this->formatInheritedSetting($late_fee, 'dollars');
			$this->formatInheritedSetting($cancellation_deadline, 'hours before course');
      if ($isReadOnly) {
        $this->formatInheritedSetting($enrollment_deadline, 'before course');
      }
      else {
        $this->formatInheritedSetting($enrollment_deadline, 'before course (Include the unit of time, e.g. "12 hours" or "2 days")');
      }
			$this->formatInheritedSetting($late_fee_deadline, 'hours before course');
			$this->formatInheritedSetting($payment_deadline, 'hours before course');

			$this->fireToAddOverridableSetting($course, $isReadOnly);
		
			if ($isReadOnly) {
				$legend = 'Overridden Location Settings';
				if ($course->hasOverriddenSettings()) {
					$this->createOverridableSettingsDisplayGroup($legend);
				}
					
				foreach (A25_Record_Course::$overridableSettings as $field)
					if (is_null($course->$field))
						$this->removeElement($field);
			} else {
				$legend = 'Override Location Settings';
				$sublegend = '(leave these blank unless this course has special, unique rules)';
				$this->createOverridableSettingsDisplayGroup($legend, $sublegend);
			}
		}

		parent::__construct($course, $returnUrl, $isReadOnly);
    }
	
	public function formatInheritedSetting(Zend_Form_Element $element, $keyword = null)
	{
		if ($this->_isReadOnly)
			$this->formatInheritedSettingForReadOnly($element, $keyword);
		else
			$this->formatInheritedSettingForEdit($element, $keyword);
	}
	
	private function formatInheritedSettingForEdit(Zend_Form_Element $element, $keyword = null)
	{
		$element->setDescription($keyword . ' (Location\'s value: '
				. $this->_location->getSetting($element->getName()) . ')');
		$decorator = $element->getDecorator('Description');
		$decorator->setOptions(array('tag' => 'span'));
	}
	
	private function formatInheritedSettingForReadOnly(Zend_Form_Element $element, $keyword = null)
	{
		$element->setDescription($keyword);
		$decorator = $element->getDecorator('Description');
		$decorator->setOptions(array('tag' => 'span'));
	}
	
	private function createOverridableSettingsDisplayGroup($legend, $sublegend = null)
	{
		$this->addDisplayGroup($this->overridableSettings,
			'inherited_values',
			array(
				'legend' => $legend,
				'sublegend' => $sublegend,
				'decorators' => array(
					'FormElements',
					/**
					 * A25_Form_Decorator_FieldsetOfTableRows
					 */
					'FieldsetOfTableRows',
				)
			));
	}

	protected function redirect()
	{
		A25_DI::Redirector()->redirectBasedOnSiteRoot(
			'/administrator/index2.php?option=com_course&task=viewA&id='
					. $this->_record->course_id, $this->successMessage
		);
	}

	private function fireToAddOverridableSetting(A25_Record_Course $course, $isReadOnly)
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_AdminUi) {
				$listener->duringCourseEditFormAddOverridableSetting($this, $course, $isReadOnly);
			}
		}
	}
	
	public function addElement($element, $name = null, $options = null) {
		parent::addElement($element, $name, $options);
		
		$element->setDecorators(array(
			'ViewHelper',
			'Errors',
			'Description',
			array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
			array('Label', array('tag' => 'td')),
			array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
		));
		
		if ($element instanceof Zend_Form_Element_Submit)
			$element->setDecorators(array(
				'ViewHelper',
				'Errors',
				'Description',
				'CancelLink',
				array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
				array('Label', array('tag' => 'td')),
				array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
			));
			

		return $this;
	}
	
	private function limitCourseCapacityForInstructors($course_capacity)
	{
		if (PlatformConfig::allowInstructorsToEditCourseCapacity)
			return;
		
		if (!A25_DI::User()->isInstructor())
			return;
		
		$course_capacity->setAttrib('readonly', 'true');
		$help_text = '(By default, # of seats is the same for all classes at a
			particular location.  If this particular class needs a different #
			of seats, please contact the administrative office.)';
		$course_capacity->setDescription($help_text);
		
		// @todo-soon: These 2 lines are duplicated elsewhere in this file.
		$decorator = $course_capacity->getDecorator('Description');
		$decorator->setOptions(array('tag' => 'span'));
	}
	
	private function addJavascriptForLocationAjax()
	{
				$javascript = '
<script language="javascript" type="text/javascript">
jQuery(function() {
	$.ajaxSetup ({
		cache: false
	});

	$("#location_id").change(function(){
		updateInstructorChoicesBasedOnLocation();
		updateCourseCapacityBasedOnLocation();
	});
});

function updateInstructorChoicesBasedOnLocation() {
	var loadUrl = "' . A25_Link::to('/api/instructors-for-location')
			. '?id=" + $("#location_id").val();
	$("#fieldset-instructors td:nth-child(2)").html("Loading...").load(loadUrl);
}

function updateCourseCapacityBasedOnLocation() {
	var loadUrl = "' . A25_Link::to('/api/number-of-seats-for-location')
			. '?id=" + $("#location_id").val();
	$.ajax({
		url: loadUrl,
		success: function(data) {
			if (data > 0)
				$("#course_capacity").val(data);
		}
	});
}
</script>';
		
		A25_DI::HtmlHead()->append($javascript);
	}
	
	protected function createInstructor1Field($location, $isReadOnly, $course)
	{
		$instructor_id = new A25_Form_Element_Select_Instructor('instructor_id',
				$location);
		$instructor_id = $this->appendCurrentInstructorIfNecessary(
				$instructor_id, $course->instructor_id);
        $instructor_id->setRequired(false)
				->setLabel('Instructor 1');
		$this->addElement($instructor_id);
		$instructor_id->setDecorators(array(
				'ViewHelper',
				'Errors',
				array('Description', array('tag' => 'span'))
		));
		if ($course->instructor_2_id > 0 || !$isReadOnly)
			$instructor_id->setDescription('&');
		else
			$instructor_id->setDescription('');
	}
	
	private function appendCurrentInstructorIfNecessary(
			A25_Form_Element_Select_Instructor $instructor_select,
			$instructor_id)
	{
		if ($instructor_id) {
			$selected = A25_Record_User::retrieve($instructor_id);
			$choices[$instructor_id] = $selected->name;
			$instructor_select->addMultiOptions($choices);
		}
		
		return $instructor_select;
	}

	private function fireDuringEditCourseForm()
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_EditCourse) {
				$listener->duringEditCourseForm($this);
			}
		}
	}
}
