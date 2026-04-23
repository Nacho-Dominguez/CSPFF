<?php

/**
 * Represents a row from DB table jos_order_item.
 */
class A25_Record_Coupon extends JosCoupon
{
	/**
	 * @param integer $id
	 * @return A25_Record_Coupon
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_Coupon')->find($id);
    }

	public function check()
	{
		return true;
	}

	/**
	 *
	 * @param string $code
	 * @return A25_Record_Coupon
	 */
	public static function loadByCode($code)
	{
		$finder = new A25_MosDbFinder(
				'A25_Record_Coupon', A25_DI::DB());
		$list = $finder->loadRecordsWithForeignKey('code', $code);
		return $list[0];
	}
}
