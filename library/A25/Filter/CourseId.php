<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_CourseId extends A25_Filter
{
	protected $course_id;
  protected $element_name = 'c';
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->course_id) {
			$q->andWhereIn($this->element_name . '.course_id', $this->course_id);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Course ID';
	}
	
	protected function field()
	{
		$element = new Zend_Form_Element_Text('course_id');
		$element->setAttrib('size',5);
		$element->setAttrib('maxlength', 5);
		$element->addFilter(new Zend_Filter_Digits());
		
		$element->setValue($this->course_id);
		
		$element->removeDecorator('label');
		$element->removeDecorator('HtmlTag');
		
		return $element->render(new Zend_View());
	}
}