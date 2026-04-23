<?php
interface A25_ListenerI_AdminUi
{
	public function afterLocationEditForm(A25_Record_LocationAbstract $location);
	public function duringCourseEditFormAddOverridableSetting(A25_Form_Record_Course $courseForm,
			A25_Record_Course $course, $isReadOnly);
}
