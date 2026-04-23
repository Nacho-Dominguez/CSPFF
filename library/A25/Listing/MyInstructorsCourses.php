<?php

/**
 * @todo-soon - move this file to the InstructorTrainer plugin directory. 
 */
class A25_Listing_MyInstructorsCourses extends A25_Listing
{
	public function __construct($limit, $offset)
	{
		parent::__construct($limit, $offset);
		
		$this->filters = array(
			new A25_Filter_CourseDate(),
			new A25_Filter_Trainees()
		);
    
		A25_DI::HtmlHead()->stylesheet('/templates/aliveat25/css/a25_filters_forReports.css');
	}

	protected function formatRow(A25_DoctrineRecord $course)
	{
    $formatter = new RowFormatterForMyInstructorsCourses($course);
    return $formatter->formatRow();
	}
	
	protected function query()
	{
		// @todo-soon - remove duplication in creating csv string.
    // 
		// Unfortunately, we cannot use Doctrine's built-in support of non-equal
		// nest relations such as this one due to a bug filed at
		// http://www.doctrine-project.org/jira/browse/DC-952.  Instead, for now,
		// we have to query the InstructorTrainer table individually in order to
		// get the trainees:
		$trainee_ids = array();
		$trainees = Doctrine_Query::create()
			->from('A25_Record_InstructorTrainer t')
			->where('t.trainer_user_id = ?', A25_DI::UserId())->execute();
		
		foreach ($trainees as $trainee) {
			$trainee_ids[] = $trainee->trainee_user_id;
		}
		$trainee_ids_string = implode(',', $trainee_ids);
		
		$q = Doctrine_Query::create()
			->from('A25_Record_Course c')
			->innerJoin('c.Instructor i')
			->leftJoin('c.Instructor2 i2')
			->leftJoin('c.Enrollments e')
			->orderBy('c.course_start_date DESC');
		
		if (count($trainees))
			$q->where('(i.id IN (' . $trainee_ids_string
					. ') OR i2.id IN (' . $trainee_ids_string . '))');
		else
			$q->where('1 = 0');

		return $q;
	}

	protected function name()
	{
		return 'Course';
	}
	
  /**
   * @todo-soon - Remove duplication with A25_Report->heading() and A25_Listing_Courses
   */
	protected function heading()
	{
		?>
		<form action="ListCoursesOfTrainees" method="get" name="adminForm">
		<?php
		$this->filters();
		?>
		<h1 style="background: url(images/generic.png) no-repeat left;
			text-align: left;
			padding: 12px;
			width: 99%;
			padding-left: 50px;
			border-bottom: 5px solid #fff;
			color: #C64934;
			font-size: 18px;">
			My Instructors' Courses
		</h1>
		<?php
	}

	protected function filters()
	{
		// @todo - instead of using the deprecated joomla calendar, use jQuery
		// UI DatePicker instead.  An example is in the Course Edit form.
		mosCommonHTML::loadCalendar();
		
		foreach ($this->filters as $filter) {
			echo $filter->htmlFormElement();
		}

		?>
		<div style="float:right; clear: right;">
			<input type="submit" value="Filter" /><br/>
		</div>
		<?php
	}
}

class RowFormatterForMyInstructorsCourses extends A25_Listing_RowFormatterForListCourses
{
  protected function dateTime()
  {
    return $this->course->getFormattedDateTime();
  }
}