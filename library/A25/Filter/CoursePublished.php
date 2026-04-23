<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_CoursePublished extends A25_Filter
{
	/**
	 * @var array
	 */
	protected $course_published_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->course_published_ids) {
			$q->andWhereIn('c.published', $this->course_published_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Published?';
	}
	
	/**
	 * @todo-soon - remove duplication with A25_Filter::generateMultiSelect()
	 */
	protected function field()
	{
		$field_name = 'course_published_ids';
		
		
		$element = new Zend_Form_Element_Multiselect($field_name);

		$options[''] = '-- All --';
		$options['1'] = 'Published';
		$options['0'] = 'Not published';
		
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