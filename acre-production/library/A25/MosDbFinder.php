<?php
/**
 * This class has general functions to find items in a subclass of mosDBTable.
 *
 * @author Thomas Albright
 */
class A25_MosDbFinder
{
	private $_className;
	private $_db;

	function __construct($className,$db)
	{
		$this->_className = $className;
		$this->_db = $db;
	}

	/**
	 * Once everything is using Doctrine, this function can become simply:
	 *
		$query = Doctrine_Query::create()
				->from("$classname a")
				->where("a.$keyname = $key");
		$result = $query->execute();

	    // is this foreach loop really necessary?
		$array = array();
		foreach ($result as $pay)
			array_push($array, $pay);
		return $array;
	 *
	 *
	 * The name of this function is a little deceiving.  It does retrieve all
	 * records that have a particular foreign key value, but it actually works
	 * for non-foreign-key fields as well.
	 *
	 * @param string $keyField - the name of the foreign key field that we want
	 * to grab records based off of
	 *
	 * @param int $keyValue - the foreign key id.  Any objects with this ID in
	 * their field named $keyField will be returned.
	 *
	 * @return An array of objects of type $this->_className
	 */
	public function loadRecordsWithForeignKey($keyField, $keyValue)
	{
		if (!$keyValue)
			throw new A25_Exception_NullRecord();
		$object = new $this->_className ();
		return $this->loadFromXref($object->getTableName(), $keyField, $keyValue);
	}
	public function loadFromXref($tableName, $keyField, $keyValue)
	{
		$object = new $this->_className ();
		$sql = "SELECT " . $object->getPrimaryKeyFieldName() . " FROM $tableName
				WHERE $keyField=";
		
		// Quote the value if it is a string:
		if (is_string($keyValue))
			$sql .= "'$keyValue'";
		else
			$sql .= $keyValue;
		
		return $this->_loadObjectsFromQuery($sql);
	}
	public function loadAllObjects()
	{
		$object = new $this->_className ();
		$sql = "SELECT " . $object->getPrimaryKeyFieldName() . " FROM "
			 . $object->getTableName();
		return $this->_loadObjectsFromQuery($sql);
	}
	private function _loadObjectsFromQuery($sql)
	{
		A25_DI::DB()->setQuery($sql);
		$idArrayOfObjectsThatHaveForeignKey = A25_DI::DB()->loadResultArray();
		if (A25_DI::DB()->_errorMsg)
			throw new Exception (A25_DI::DB()->_errorMsg);
		$objects = array();
		if ($idArrayOfObjectsThatHaveForeignKey)
			foreach ($idArrayOfObjectsThatHaveForeignKey as $id) {
				$newObject = new $this->_className ();
				$newObject->load($id);
				array_push($objects,$newObject);
			}
		return $objects;
	}
}
?>
