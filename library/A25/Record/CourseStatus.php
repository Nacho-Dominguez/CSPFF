<?php

class A25_Record_CourseStatus extends JosCourseStatus implements A25_ISelectable
{
	/**
	 * @param integer $id
	 * @return A25_Record_CourseType
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_CourseType')->find($id);
    }
	public function getSelectionName() {
		return $this->status_name;
	}
}
