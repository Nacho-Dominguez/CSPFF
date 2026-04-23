<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_Zip extends A25_Filter
{
	protected $zip;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->zip) {
			$q->andWhereIn('s.zip', $this->zip);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Zip Code';
	}
	
	protected function field()
	{
		$element = new Zend_Form_Element_Text('zip');
		$element->setAttrib('size',5);
		$element->setAttrib('maxlength', 5);
		$element->addFilter(new Zend_Filter_Digits());
		
		$element->setValue($this->zip);
		
		$element->removeDecorator('label');
		$element->removeDecorator('HtmlTag');
		
		return $element->render(new Zend_View());
	}
}