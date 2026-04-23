<?php

class A25_Record_HearAboutType extends JosHearAboutType
{
	/**
	 * Checks object variables
	 * @author Scott Golaszewski
	 * @version January 8, 2007
	 *
	 * @return boolean
	 */
	function check() {

		return true;
	}

	/**
	 * @param integer $id
	 * @return A25_Record_HearAboutType
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_HearAboutType')->find($id);
	}

    public function getSelectionName()
    {
        return $this->hear_about_name;
    }
}
