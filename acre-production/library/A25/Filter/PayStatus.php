<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_PayStatus extends A25_Filter
{
	/**
	 * @var array
	 */
	protected $pay_status_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->pay_status_ids) {
			$q->andWhereIn('o.pay_status_id', $this->pay_status_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Payment Status';
	}
	
	/**
	 * @todo-soon - remove duplication with A25_Filter::generateMultiSelect()
	 */
	protected function field()
	{
		$field_name = 'pay_status_ids';
		
		
		$element = new Zend_Form_Element_Multiselect($field_name);

		$options[''] = '-- All --';
		$options[A25_Record_Order::payStatus_paid] = 'Paid';
		$options[A25_Record_Order::payStatus_unpaid] = 'Not paid';
		
		$element->addMultiOptions($options);
		if ($this->$field_name)
			$element->setValue($this->$field_name);
		else
			$element->setValue('');
		
		$element->removeDecorator('label');
		$element->removeDecorator('HtmlTag');
		
		return $element->render(new Zend_View());
	}
}