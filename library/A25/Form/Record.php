<?php

class A25_Form_Record extends A25_Form
{
	/**
	 * @var A25_DoctrineRecord
	 */
	protected $_record;
	
	/**
	 * @var boolean
	 */
	protected $_isReadOnly;

	/**
	 *
	 * @param ISelectable array $records
	 * @return array 
	 */
	public static function createSelectionList($records)
	{
		$selections = array();
		foreach ($records as $record) {
			$selections[$record->getSelectionValue()] =
					$record->getSelectionName();
		}
		return $selections;
	}

	public function __construct($record, $redirectToQuerystring,
		$isReadOnly = false)
	{
		$this->_record = $record;
		$this->_isReadOnly = $isReadOnly;

        parent::__construct($redirectToQuerystring);
	}

	protected function generateSaveButton()
	{
		if (!$this->_isReadOnly)
			parent::generateSaveButton();
	}

	public function run($data)
	{
		$this->setElementModes();
		parent::run($data);
	}

	protected function setElementModes()
	{
		foreach ($this->getElements() as $element)
			if ($element instanceof A25_Form_Element && $this->_isReadOnly)
				$element->setReadOnly();
	}
	
	protected function populateAndSaveIfNecessary($data)
	{
		if ($data)
			return parent::populateAndSaveIfNecessary($data);
		else
			$this->populate($this->_record->toArrayWithSameId());
	}
	
	protected function save()
	{
		foreach ($this->getElements() as $element) {
			$this->setProperty($element);
		}
		return $this->saveAndReturnMessage();
	}
	
	protected function saveAndReturnMessage()
	{
		try {
			$this->_record->checkAndStore();
		} catch (A25_Exception_DataConstraint $e) {
			return $e->getMessage();
		}
		return $this->successMessage;
	}
	
	protected function setProperty(Zend_Form_Element $element)
	{
		if ($element instanceof Zend_Form_Element_Submit || $element instanceof A25_Form_PlainText)
			return;

		$this->_record->setProperty($element->getName(), $element->getValue());
	}
}
