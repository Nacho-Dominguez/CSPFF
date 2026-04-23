<?php
require_once(dirname(__FILE__) . '/admin.course.html.php');

function fireExtraCourseActionsButtons($course_id)
{
  foreach (A25_ListenerManager::all() as $listener)
  {
    if ($listener instanceof A25_ListenerI_CourseActions)
      $listener->extraCourseActionButtons($course_id);
  }
}

$link = 'edit-course?id=' . $row->course_id;
ADMIN_HTML_course::quickiconButton( $link, 'addedit.png', 'Edit Course' );

$link = 'index2.php?option=com_course&task=newmsg&id=' . $row->course_id;
ADMIN_HTML_course::quickiconButton( $link, 'inbox.png', 'Email all students' );

$link = 'components/com_course/admin.course.print.html.php?option=com_course&task=viewRoster&id=' . $row->course_id;
ADMIN_HTML_course::quickiconButtonNewWindow( $link, 'printer.png', 'Print Course Roster' );

if (A25_DI::User()->isAdminOrHigher()) {
  $link = 'index2.php?&option=com_stats&task=enrollment&course_id=' . $row->course_id;
  ADMIN_HTML_course::quickiconButtonNewWindow( $link, 'generic.png', 'Enrollment Details' );
}

if (in_array($row->status_id,array(1,2))) {
	$link = 'index2.php?option=com_course&task=cancelform&id=' . $row->course_id;
	ADMIN_HTML_course::quickiconButton( $link, 'cancel_f2.png', 'Cancel Course' );
}

fireExtraCourseActionsButtons($row->course_id);