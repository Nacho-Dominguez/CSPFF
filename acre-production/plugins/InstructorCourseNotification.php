<?php
//This plugin notifies instructors when they are assigned to a course
class A25_Plugin_InstructorCourseNotification implements A25_ListenerI_Doctrine
{
	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
	{
		if ($doctrineRecord instanceof A25_Record_Course)
		{
            $doctrineRecord->hasColumn('instructor_notified', 'integer', 4, array(
                 'type' => 'integer',
                 'length' => 1,
                 'fixed' => false,
                 'unsigned' => false,
                 'primary' => false,
                 'notnull' => false,
                 'autoincrement' => false,
                 ));
            $doctrineRecord->hasColumn('instructor_2_notified', 'integer', 4, array(
                 'type' => 'integer',
                 'length' => 1,
                 'fixed' => false,
                 'unsigned' => false,
                 'primary' => false,
                 'notnull' => false,
                 'autoincrement' => false,
                 ));
		}
	}
}