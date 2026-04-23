<?php

class A25_Record_EnrollStatus extends JosEnrollStatus implements A25_ISelectable
{
	/**
	 * @param integer $id
	 * @return A25_Record_EnrollStatus
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_EnrollStatus')->find($id);
    }
	
	public function getSelectionName()
	{
		return $this->status_name;
	}
}
?>
