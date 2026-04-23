<?php

class A25_Plugin_Survey implements A25_ListenerI_CourseActions
{
  public function extraCourseActionButtons($course_id)
  {
    $link = A25_Link::withoutSef('/custom/A25/Plugin/Survey/print.php?id=' . $course_id);
    ADMIN_HTML_course::quickiconButton( $link, 'printer.png', 'Print Course Evaluation' );
  }
}

set_include_path(
	ServerConfig::webRoot . '/plugins/Survey' . PATH_SEPARATOR
	. get_include_path()
);