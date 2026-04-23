<?php

class A25_Record_State extends JosUsState
{
	/**
	 * @param integer $id
	 * @return A25_Record_State
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_State')->find($id);
    }
	public static function  retrieveAllStateCodes()
	{
		$sql = 'SELECT state_code FROM jos_us_state';
		$result = A25_DI::DB()->setQuery($sql);
		return array();
	}
}
?>
