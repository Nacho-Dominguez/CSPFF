<?php

// Provides class mosDbTable:
require_once(dirname(__FILE__) . '/../../includes/database.php');

class A25_Record extends mosDbTable
{
	public function __construct($tableName, $primaryKey)
	{
		$this->mosDBTable($tableName, $primaryKey, A25_DI::DB() );
	}
	protected static function retrieveRecord($className,$id)
	{
		$wholeClassName = "A25_$className";
		$locationRecord = new $wholeClassName();
		$locationRecord->loadWithException($id);
		return $locationRecord;
    }
	protected function loadWithException($id)
	{
		if ($id == null)
			throw new Exception('ID not defined');
		if (!$this->load($id))
			throw new A25_Exception_DataConstraint(get_class($this) . " with ID='$id' not found");
    }
	public function store()
	{
		$this->addCreationInfo();
		return parent::store();
	}
	public function storeWithExceptionOnFailure()
	{
		if (!$this->store()) {
			if (preg_match('/Duplicate entry/', $this->_error))
				throw new A25_Exception_DataConstraint(
						'This is a duplicate entry, so it was not saved');
			else
				throw new Exception ($this->_error);
		}
	}
	public function checkWithExceptionOnFailure()
	{
		if (!$this->check())
			throw new A25_Exception_DataConstraint($this->_error);
    }
	/**
	 * @deprecated
	 */
	public function checkAndStore()
	{
		$this->save();
    }
	public function save()
	{
		$this->checkWithExceptionOnFailure();
		$this->storeWithExceptionOnFailure();
		return true;
	}
	private function addCreationInfo()
	{
		if (property_exists($this, 'created_by'))
			$this->created_by =
					$this->created_by ? $this->created_by : A25_DI::UserId();

		if (property_exists($this, 'created') && $this->created == 0)
			$this->created = date( 'Y-m-d H:i:s' );

		if (property_exists($this, 'modified_by'))
			$this->modified_by = A25_DI::UserId();

		if (property_exists($this, 'modified'))
			$this->modified = date( 'Y-m-d H:i:s' );
	}
	public function setProperty($field, $value)
	{
		if(property_exists($this,$field)) {
			$this->$field = $value;
		}
	}

	/**
	 * This is for subclasses which implement ISelectable.
	 */
	public function getSelectionValue()
	{
		$key = $this->_tbl_key;
		return $this->$key;
	}

	public function getTableName()
	{
		return $this->_tbl;
	}

	public function getPrimaryKeyFieldName()
	{
		return $this->_tbl_key;
	}
	public function toArrayWithSameId()
	{
		return (array)$this;
	}
}
?>
