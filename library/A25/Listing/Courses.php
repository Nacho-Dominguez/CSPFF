<?php

class A25_Listing_Courses extends A25_Listing
{
  protected $item_name = 'courses';
  
  private $dateFilter;
  private $idFilter;
  private $publishedFilter;
  private $statusFilter;
  private $typeFilter;
  private $instructorFilter;
  private $locationFilter;

	public function __construct($limit, $offset)
	{
		parent::__construct($limit, $offset);
		
    $render = 'return \'<div class="filter_title">\'. $this->title() . \':</div><div class="filter_field">\'. $this->field() . \'</div>\';';
    $this->dateFilter = new A25_Filter_CourseDate($render);
    $this->idFilter = new A25_Filter_CourseId($render);
    $this->publishedFilter = new A25_Filter_CoursePublished($render);
    $this->statusFilter = new A25_Filter_CourseStatus($render);
    $this->typeFilter = new A25_Filter_CourseType($render);
    $this->instructorFilter = new A25_Filter_Instructor($render);
    $this->locationFilter = new A25_Filter_Location($render);
    
		$this->filters = array(
      $this->dateFilter,
      $this->idFilter,
      $this->publishedFilter,
      $this->statusFilter,
      $this->typeFilter,
      $this->instructorFilter,
      $this->locationFilter
		);
	}

	protected function formatRow(A25_DoctrineRecord $course)
	{
    $formatter = new A25_Listing_RowFormatterForListCourses($course);
    return $formatter->formatRow();
	}
	
	protected function query()
	{
		$q = Doctrine_Query::create()
			->from('A25_Record_Course c')
			->orderBy('c.course_start_date DESC');
    
    if (A25_DI::User()->isInstructor())
      $q = $this->limitWhichCoursesInstructorsSee ($q);

		return $q;
	}
  
  /**
   * Instructors can only see courses which are either:
   * - Courses they taught
   * - At a location they have permissions for
   */
  private function limitWhichCoursesInstructorsSee($query)
  {
      $locations = implode(',', A25_Record_Location::getLocs());
      $query->andWhere ('((c.instructor_id = ' . A25_DI::UserId ()
          . ' OR c.instructor_2_id = ' . A25_DI::UserId() . ')'
          . ' OR c.location_id IN (' . $locations . '))');
      return $query;
  }
  
  /**
   * @todo-soon - Remove duplication with A25_Report->heading() and A25_Listing_Courses
   */
	protected function heading()
	{
		?>
      <a href="<?php echo A25_Link::to('/administrator/edit-course');?>"
       style="display: block; float: right; border:  1px solid #e1e1e1; padding: 6px;">
      <img src="<?php echo A25_Link::to('/administrator/images/new_f2.png');?>"
        style="vertical-align: middle; border: none;" border=0 />
        Create a new course
      </a>
		<form action="list-courses" method="get" name="adminForm" id="adminForm">
		<h1 style="background: url(images/generic.png) no-repeat left;
			text-align: left;
			padding: 12px;
			width: 99%;
			padding-left: 50px;
			border-bottom: 5px solid #fff;
			color: #C64934;
			font-size: 18px;">
			List Courses
		</h1>
		<?php
    $this->filters();
    
    A25_DI::HtmlHead()->append('<style type="text/css">
      .result_stats {
        text-align: left;
        color: #999;
        margin-bottom: 1em;
        margin-top: 1em;
        font-size: 13px;
      }
      .pagenav_list {
        padding: 13px;
      }
    </style>');
    
    if (A25_DI::User()->isInstructor()) {
      echo "<p class='result_stats' style='float: right'>
        (Only showing courses that you taught or are at your locations)
        </p>";
    }
    
    $this->displayNavStatus();
	}

	protected function filters()
	{
		// @todo - instead of using the deprecated joomla calendar, use jQuery
		// UI DatePicker instead.  An example is in the Course Edit form.
		mosCommonHTML::loadCalendar();
    A25_Include_1140CssGrid::load();
    A25_DI::HtmlHead()->append('<style type="text/css">
      .filter_title {
        float: left;
        clear: both;
        min-height: 1px;
        width: 20%;
        margin-right: 2.5%;
        text-align: right;
        color: #555;
      }

      .filter_field {
        width: 75%;
        text-align: left;
        float: left;
        min-height: 1px;
      }
      .date_range {
        float: left;
        text-align: left;
      }
    </style>');
    A25_DI::HtmlHead()->includeJquery();
    A25_DI::HtmlHead()->append('<script type="text/javascript">
      jQuery(function() {
        $.fn.clearForm = function() {
          return this.each(function() {
            var type = this.type, tag = this.tagName.toLowerCase();
            if (tag == "form")
              return $(":input",this).clearForm();
            if (type == "text" || type == "password" || tag == "textarea")
              this.value = "";
            else if (type == "checkbox" || type == "radio")
              this.checked = false;
            else if (tag == "select")
              this.selectedIndex = 0;
          });
        };
        $(document).ready(function() {
          // use this to reset a single form
          $("#reset").click(function() {
            $("#adminForm").clearForm();
          });
        });
        $("#course_date_from").change(function() {
					if (!$("#course_date_to").val()) {
            var value = $(this).val();
            $("#course_date_to").val(value);
					}
				});
      });
    </script>');
		?>
    <div class="container" style="padding-top: 18px; padding-bottom: 1px;
         background-color: #F1F1F1;">
      <!--<h1 style="margin: 12px; color: #844">Courses</h3>-->
      <div class="row">
        <div class="onecol"></div>
        <div class="fivecol">
          <?php echo $this->idFilter->htmlFormElement(); ?>
          <?php echo $this->dateFilter->htmlFormElement(); ?>
          <?php echo $this->instructorFilter->htmlFormElement(); ?>
          <?php echo $this->locationFilter->htmlFormElement(); ?>
        </div>
        <div class="fivecol">
          <?php echo $this->publishedFilter->htmlFormElement(); ?>
          <?php echo $this->statusFilter->htmlFormElement(); ?>
          <?php echo $this->typeFilter->htmlFormElement(); ?>
        </div>
        <div class="onecol"></div>
      </div>
      <div style="margin: 12px;">
        <input type="submit" style="font-size: 16px;"value="Search" />
          &nbsp;|&nbsp; <a id="reset" href="javascript:void(0);">Reset</a>
      </div>
    </div>
		<?php
	}
}