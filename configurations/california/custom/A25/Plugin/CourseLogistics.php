<?php

class A25_Plugin_CourseLogistics implements A25_ListenerI_EditCourse,
    A25_ListenerI_Doctrine, A25_ListenerI_AppendListCoursesFormatRow
{
  public $logistics = array('classroom_set', 'instructor_confirmed',
      'materials_sent', 'materials_received', 'instructor_paid', 'certificates_sent');
  
  public function duringEditCourseForm(A25_Form_Record_Course $courseForm)
  {
    $classroom = new A25_Form_Element_Checkbox('classroom_set');
    $courseForm->addElement($classroom);
    $confirm = new A25_Form_Element_Checkbox('instructor_confirmed');
    $courseForm->addElement($confirm);
    $sent = new A25_Form_Element_Checkbox('materials_sent');
    $courseForm->addElement($sent);
    $received = new A25_Form_Element_Checkbox('materials_received');
    $courseForm->addElement($received);
    $paid = new A25_Form_Element_Checkbox('instructor_paid');
    $courseForm->addElement($paid);
    $certs = new A25_Form_Element_Checkbox('certificates_sent');
    $courseForm->addElement($certs);
    
    $legend = 'Course Logistics';
    $sublegend = '(these settings are not visible to students)';
    $this->createCourseLogisticsDisplayGroup($courseForm, $legend, $sublegend);
  }
  
	private function createCourseLogisticsDisplayGroup($courseForm, $legend, $sublegend)
	{
		$courseForm->addDisplayGroup($this->logistics,
			'course_logistics',
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
  
  public function appendListCoursesFormatRow(array $formatRow,
      A25_DoctrineRecord $course)
  {
    $head = A25_DI::HtmlHead();
    $head->includeJqueryUI();
    $head->append('
    <style>
      .tooltip {
            display: inline-block;
          }
    </style>');
    $head->append('
      <script type="text/javascript">
      $(function() {
          $( document ).tooltip({
            track: true,
            tooltipClass: "tooltip",
            content: function() {
              return "<img src=\'' . A25_Link::to('/plugins/CourseLogistics/images/boxlegend.svg') . '\' />";
            }
          });
      });
      </script>
    ');
    
    $box = $this->chooseBox($course);
		$formatRow['Logistics <a href="javascript:void()" title="Logistics">What\'s this?</a>'] = $box;
		return $formatRow;
  }
  
  private function chooseBox(A25_DoctrineRecord $course)
  {
    $sum = 0;
    if ($course->classroom_set == 1)
      $sum++;
    if ($course->instructor_confirmed == 1)
      $sum++;
    if ($course->materials_sent == 1)
      $sum++;
    if ($course->course_start_date < A25_Functions::formattedDateTime())
      $sum++;
    if ($course->materials_received == 1)
      $sum++;
    if ($course->instructor_paid == 1)
      $sum++;
    if ($course->certificates_sent == 1)
      $sum++;
    
    return '<img src="' . A25_Link::to($path)
        . 'plugins/CourseLogistics/images/box' . $sum
        . '.svg" height="30" width="30">';
  }
  
	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
	{
		if ($doctrineRecord instanceof A25_Record_Course)
		{
			$doctrineRecord->hasColumn('classroom_set', 'integer', 1, array(
          'type' => 'integer',
          'length' => 1,
          'fixed' => false,
          'unsigned' => false,
          'primary' => false,
          'default' => '0',
          'notnull' => true,
          'autoincrement' => false,
          ));
      $doctrineRecord->hasColumn('instructor_confirmed', 'integer', 1, array(
          'type' => 'integer',
          'length' => 1,
          'fixed' => false,
          'unsigned' => false,
          'primary' => false,
          'default' => '0',
          'notnull' => true,
          'autoincrement' => false,
          ));
      $doctrineRecord->hasColumn('materials_sent', 'integer', 1, array(
          'type' => 'integer',
          'length' => 1,
          'fixed' => false,
          'unsigned' => false,
          'primary' => false,
          'default' => '0',
          'notnull' => true,
          'autoincrement' => false,
          ));
      $doctrineRecord->hasColumn('materials_received', 'integer', 1, array(
          'type' => 'integer',
          'length' => 1,
          'fixed' => false,
          'unsigned' => false,
          'primary' => false,
          'default' => '0',
          'notnull' => true,
          'autoincrement' => false,
          ));
      $doctrineRecord->hasColumn('instructor_paid', 'integer', 1, array(
          'type' => 'integer',
          'length' => 1,
          'fixed' => false,
          'unsigned' => false,
          'primary' => false,
          'default' => '0',
          'notnull' => true,
          'autoincrement' => false,
          ));
      $doctrineRecord->hasColumn('certificates_sent', 'integer', 1, array(
          'type' => 'integer',
          'length' => 1,
          'fixed' => false,
          'unsigned' => false,
          'primary' => false,
          'default' => '0',
          'notnull' => true,
          'autoincrement' => false,
          ));
		}
	}
}