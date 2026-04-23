<?php

class A25_Record_PaymentType extends JosPayType
{
	/**
	 * @param integer $id
	 * @return A25_Record_PaymentType
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_PaymentType')->find($id);
    }
}
?>
