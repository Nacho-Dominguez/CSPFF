<?php

class A25_Form_Validate_Unique extends Zend_Validate_Abstract
{
    const DUPLICATE = 'foundADuplicate';

    protected $_messageTemplates = array(self::DUPLICATE =>
            'This value is already taken.  Please choose another.');

    private $_recordClassName;
    private $_uniqueField;

    public function __construct(Doctrine_Record $record, $uniqueField)
    {
        $this->_recordClassName = get_class($record);
        $this->_uniqueField = $uniqueField;
    }

    /**
    * Check if the element using this validator is valid
    *
    * @param $value string
    * @return boolean Returns true if the element is valid
    */
    public function isValid($value)
    {
        $this->_setValue($value);

        $q = Doctrine_Query::create()
            ->from("$this->_recordClassName  a")
            ->where("a.$this->_uniqueField  = ?", $value);

        if ($q->count() > 0) {
            $this->_error(self::DUPLICATE);
            return false;
        } else {
            return true;
        }
    }
}
