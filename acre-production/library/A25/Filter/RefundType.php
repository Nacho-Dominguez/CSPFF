<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 * 
 * @todo - write those tests
 */
class A25_Filter_RefundType extends A25_Filter
{
	/**
	 * @var array
	 */
	protected $refund_type_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->refund_type_ids) {
			$q->andWhereIn('p.refund_type_id', $this->refund_type_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Type';
	}
	
	protected function field()
	{
		return $this->generateMultiSelect('refund_type_ids',
				'A25_Record_OrderItemType');
	}
}