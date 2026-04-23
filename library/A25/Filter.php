<?php

/**
 * 
 * @testing
 * When you inherit this class, you should create an integration test for
 * modifyQuery() filtering correctly, and a unit test that it does nothing when
 * the value is not set.  No other functions in the child class need to have tests
 * written for them, since they really require testing at the browser level and
 * these won't change often enough to warrant the cost of a Selenium test.  Just
 * test those functions indirectly by hand-testing in the browser.
 */
abstract class A25_Filter extends A25_StrictObject
{
  private $html_render_code =
      'return \'<div class="filter">\'. $this->title() . \':<br/>\'. $this->field() . \'</div>\';';
	/**
	 * The constructor automatically maps the QueryString to the object
	 * properties.  Because of this, all that is needed to add a new GET field
	 * to a subclass is to declare its property.
	 * 
	 * @testing - no direct testing is needed, because this will be
	 * executed by automated tests for subclasses' modifyQuery().
	 */
	public function __construct($html_render_code = null)
	{
    if ($html_render_code)
      $this->html_render_code = $html_render_code;
    
		$reflect = new ReflectionClass($this);
		$properties = $reflect->getProperties();
		
		$get = A25_DI::QueryString();
		
		foreach ($properties as $property) {
			$propertyName = $property->getName();
			
			if (is_array($get[$propertyName])) {
				$all = false;
				foreach($get[$propertyName] as $value) {
					if ($value == '') {
						$all = true;
						break;
					}
				}
				if ($all)
					continue;
			}
				
			$this->$propertyName = $get[$propertyName];
		}
	}
	
	/**
	 * @testing - Hand test only, since it is about appearance
	 * 
	 * @return type 
	 */
	public function htmlFormElement()
	{
		return eval($this->html_render_code);
	}
	
	/**
	 * @testing
	 * - All subclasses should have Unit tests for verifying it changes nothing
	 *   if GET is not set
	 *   - See the existing tests for an example, but all that is needed is to
	 *     subclass test_unit_A25_Filter_ModifyQueryTestTemplate
	 * - All subclasses should have Integration tests for all edges of the
	 *   filters
	 */
	abstract public function modifyQuery(Doctrine_Query $q);
	
	/**
	 * @testing - Hand test only, since it is about appearance
	 */
	abstract protected function title();
	
	/**
	 * @testing - Hand test only, since it is about appearance
	 */
	abstract protected function field();
	
	/**
	 * @testing - No automated testing, because hand testing of different filters
	 * which use this will cover it.
	 * 
	 * @param string $field_name
	 * @param string $record_type
	 * @param string $order_by
	 * @return string
	 */
	protected function generateMultiSelect($field_name, $record_type,
			$order_by = null, $q = null)
	{
		$element = new Zend_Form_Element_Multiselect($field_name);

		if (!$q)
			$q = Doctrine_Query::create()->select()
					->from($record_type);
		
		if ($order_by)
			$q->orderBy($order_by);
		
		$records = $q->execute();

		$options[''] = '-- All --';
		$options += A25_Form_Record::createSelectionList($records);
		
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