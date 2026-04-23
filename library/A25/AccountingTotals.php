<?php

class A25_AccountingTotals {
	public static function foundationIncome()
	{
		return self::originalPayments() - self::allCreditsOwed();
	}
	public static function grossIncomeFromCourses()
	{
		return self::activeEnrollmentPayments()
				- self::activeEnrollmentNonRefundableLineItems();
	}
	public static function totalReceivablesFromCourses()
	{
		return self::activeEnrollmentPositiveLineItems()
				- self::activeEnrollmentPayments();
	}
	public static function allCreditsOwed()
	{
		return self::cancelledEnrollmentOriginalPayments()
				- self::cancelledEnrollmentNonRefundableLineItems();
	}
	/**
	 * This function is not complete.  However, at the moment, it is only used
	 * for AccountingScenariosTest, and it works well enough for that.  If we
	 * ever want to actually know the fee revenue, we would also need to add
	 * logic considering paid items and refunded items.
	 */
	public static function calculateFeeRevenue()
	{
		$q = Doctrine_Query::create()
				->select('SUM(i.unit_price) AS total')
				->from('A25_Record_OrderItem i')
				->whereNotIn('i.type_id', A25_Record_OrderItem::$refundableList);

		return self::executeDoctrineQuerySum($q);
	}
	/**
	 * In order for this to work the passed query must select a sum as total
	 *
	 * @param Doctrine_Query $q
	 * @return total from query
	 */
	private static function executeDoctrineQuerySum(Doctrine_Query $q)
	{
		$result = $q->execute();
		$total = 0.0 + $result[0]->total;

		return $total;
	}
  /**
   * @todo-active_enroll If an enrollment is "active", it really means that the
   * student would owe tuition.  Verify that no usages of it assume a different
   * meaning, then use a better word than "Active" in all of the methods that
   * are related to Enrollments.
   */
	private static function activeEnrollmentPayments()
	{
		$q = Doctrine_Query::create()
				->select('SUM(p.amount) AS total')
				->from('A25_Record_Pay p')
				->innerJoin('p.Enrollment e')
				->andWhereNotIn('e.status_id',
						A25_Record_Enroll::inactiveStatusList());

		return self::executeDoctrineQuerySum($q);
	}
	private static function cancelledEnrollmentOriginalPayments()
	{
		$enrollmentsAreActive = false;
		return self::enrollmentPayments($enrollmentsAreActive);
	}
	private static function enrollmentPayments($enrollmentsAreActive)
	{
		$q = Doctrine_Query::create()
				->select('SUM(p.amount) AS total')
				->from('A25_Record_Pay p')
				->innerJoin('p.Enrollment e');

		if($enrollmentsAreActive) {
			$q->andWhereNotIn('e.status_id',
					A25_Record_Enroll::inactiveStatusList());
		} else {
			$q->andWhereIn('e.status_id',
					A25_Record_Enroll::inactiveStatusList());
		}

		return self::executeDoctrineQuerySum($q);
	}
	private static function originalPayments()
	{
		$q = Doctrine_Query::create()
				->select('SUM(p.amount) AS total')
				->from('A25_Record_Pay p');
		return self::executeDoctrineQuerySum($q);
	}
  /**
   * @todo-active_enroll If an enrollment is "active", it really means that the
   * student would owe tuition.  Verify that no usages of it assume a different
   * meaning, then use a better word than "Active" in all of the methods that
   * are related to Enrollments.
   */
	private static function activeEnrollmentPositiveLineItems()
	{
		$q = Doctrine_Query::create()
				->select('SUM(i.unit_price) AS total')
				->from('A25_Record_OrderItem i')
				->innerJoin('i.Order o')
				->innerJoin('o.Enrollment e')
				->whereNotIn('e.status_id',
						A25_Record_Enroll::inactiveStatusList())
				->andWhere('i.unit_price > 0');

		return self::executeDoctrineQuerySum($q);
	}
	private static function cancelledEnrollmentNonRefundableLineItems()
	{
		return self::enrollmentNonRefundableLineItems(false);
	}
	public static function activeEnrollmentNonRefundableLineItems()
	{
		return self::enrollmentNonRefundableLineItems(true);
	}
	private static function enrollmentNonRefundableLineItems($enrollmentsAreActive)
	{
		$q = Doctrine_Query::create()
				->select('SUM(i.unit_price) AS total')
				->from('A25_Record_OrderItem i')
				->innerJoin('i.Order o')
				->innerJoin('o.Enrollment e')
				->whereNotIn('i.type_id', A25_Record_OrderItem::$refundableList);
		if($enrollmentsAreActive) {
			$q->andWhereNotIn('e.status_id',
					A25_Record_Enroll::inactiveStatusList());
		} else {
			$q->andWhereIn('e.status_id',
					A25_Record_Enroll::inactiveStatusList());
		}
		
		return self::executeDoctrineQuerySum($q);
	}
}
?>
