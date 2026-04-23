<?php

class A25_Filter_DonationType extends A25_Filter
{
	/**
	 * @var array
	 */
	protected $donation_type_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->donation_type_ids) {
			$q->whereIn('d.reason', $this->donation_type_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Donation Type';
	}
	
	protected function field()
	{
		$field_name = 'donation_type_ids';
		
		
		$element = new Zend_Form_Element_Multiselect($field_name);

		$options[''] = '-- All --';
		$options[A25_Record_IndependentDonation::reason_None] = 'General';
		$options[A25_Record_IndependentDonation::reason_LicensePlate] = 'License Plate';
		$options[A25_Record_IndependentDonation::reason_CourtOrder] = 'Court Order';
		
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