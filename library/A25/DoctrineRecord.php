<?php

abstract class A25_DoctrineRecord extends Doctrine_Record
{
	/**
	 * This static value can disable saving for all DoctrineRecord objects.
	 * It's useful for ensuring that no data is accidentally chaning on pages
	 * that should only be querying.  Whenever the controller enters a task
	 * that should not save anything, it is wise to set this value to true.
	 *
	 * @var boolean
	 */
	public static $disableSave = false;

	/**
	 *
	 * @var string
	 * @deprecated
	 */
	public $_error;

	public function __get($name)
	{
    // We do not allow reading of a property which starts with 'calc_',
    // because these fields are calculated from other values and are not
    // necessarily up-to-date at all times.  For example,
    // jos_order_item->calc_is_active is calculated from
    // OrderItem->isActive().  OrderItem->isActive() should always be
    // used when checking whether it is active.
    if (substr($name, 0, 5) === 'calc_')
      throw new Exception('Reading of calculated values is not
          allowed');

		// We do not allow getting of a new one-to-one item or a many-to-one item,
		// because Doctrine creates a new item by default, and that can cause
		// problems that are impossible to foresee, like saving null records
		// to the database.
		if ($this->getTable()->hasRelation($name)) {
			if ($this->getTable()->getRelation($name)->getType() == Doctrine_Relation::ONE) {
				if (!$this->relatedIsDefined($name)) {
					// If the call is from a Doctrine class, don't throw the exception:
					$backtrace = debug_backtrace();
					if (!preg_match('/^Doctrine_/', $backtrace[1]['class']))
						throw new Exception("$name has not been set yet.");
				}
			}
		}

		return parent::__get($name);
	}

	public function relatedIsDefined($name)
	{
		if (isset($this->$name))
			return true;

		$localField = $this->getTable()->getRelation($name)->getLocal();
		return ($this->$localField > 0);
	}

	/**
	 * Currently, it syncs both sides of a relationship when using relationship
	 * aliases.  It also tries to do so when using the field name directly.  For
	 * example, $student->Enroll = $enroll will work perfectly.
	 * $student->xref_id = $enroll->xref_id will sync if $enroll has already
	 * been saved to the DB.  Because it won't sync if $enroll hasn't been saved
	 * yet, we may phase out all use of field names and use aliases instead.
	 * Then again, the actual problem may never manifest itself, so it might not
	 * be worth the trouble of refactoring.
	 */
	public function __set($name, $value)
    {
		// Do this if problems arise: uncomment these 2 lines, then get all tests
		// to pass by never setting student_id directly.  See the comment above
		// for why.
//		if ($this instanceof A25_Record_Enroll && $name == 'student_id')
//			throw new Exception ("$name is read-only.  Please use its relationship aliases instead.");

		$relation = $this->relationFor($name);
		if ($relation) {
			if ($relation->getType() == Doctrine_Relation::ONE) {
				// many-to-one or one-to-one
				$inverse = $this->inverseRelation($relation);
				if ($inverse) {
					$alias = $inverse->getAlias();
					if ($value instanceof Doctrine_Record) {
						if ($inverse->getType() == Doctrine_Relation::MANY) {
							$array = $value->$alias;
							$array[] = $this;
							return;
						} else {
							parent::__set($name, $value);
							// this if() keeps us from an infinite recursion.
							if (!isset($value->$alias) || $value->$alias != $this)
								$value->$alias = $this;
							return;
						}
					} else {
						// Once we phase out all uses of field names, this branch
						// won't be necessary.
						$object = $relation->getTable()->find($value);
						if ($object) {
							if ($inverse->getType() == Doctrine_Relation::MANY) {
								$array = $object->$alias;
								$array[] = $this;
								return;
							} else {
								parent::__set($name, $value);
								// this if() keeps us from an infinite recursion.
								if (!isset($value->$alias) || $object->$alias != $this)
									$object->$alias = $this;
								return;
							}
						}
					}
				}
			} else {
				// if one-to-many or many-to-many, do nothing because Doctrine handles it.
				// Plus, we don't want to get into an infinite recursion.
			}
		}
		parent::__set($name, $value);
	}

  /**
   * A25_DoctrineRecord->_set() [1 underscore, not 2] runs after Doctrine mutators
   * but before actual data update. It is basically the last chance to modify the
   * field value.
   */
  public function _set($fieldName, $value, $load = true) {
    $column = new A25_RecordColumn($this, $fieldName);
    $value = $column->modifyDuringSet($value);

    return parent::_set($fieldName, $value, $load);
  }

	/**
	 * Be careful when overriding this in subclasses.  They should still call
	 * parent::setUp(), to be sure that the normal event firing will occur.
	 *
	 * Doctrine generated the subclasses without calling parent::setUp(), so it's
	 * possible that if you try to use A25_ListenerI_Doctrine with a class which
	 * has never used it before, you will be missing the call to parent::setUp().
	 *
	 * Requiring subclasses to call parent::setUp() is a little dangerous, since
	 * it won't be immediately obvious that something is wrong, and then it will
	 * be potentially hard to debug.  At this time, I can't think of a better
	 * way to do it, though.
	 */
  public function setUp()
  {
		parent::setTableDefinition();
		$this->fireAfterDoctrineSetup();
	}
  public function hasColumn($name, $type = null, $length = null, $options = array()) {
      // If the object _data has already been filled, define the array key so
      // that it can be __set():
      $this->_data[$name] = null;

      return parent::hasColumn($name, $type, $length, $options);
  }

	private function fireAfterDoctrineSetup()
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_Doctrine) {
				$listener->afterDoctrineSetup($this);
			}
		}
	}

	/**
	 * Public for testing only
	 *
	 * @param string $alias
	 * @return Doctrine_Relation
	 */
	public function relationFor($alias)
	{
		if ($this->getTable()->hasRelation($alias)) {
			return $this->getTable()->getRelation($alias);
		} else {
			return $this->relationForForeignKey($alias);
		}
	}
	private function relationForForeignKey($field)
	{
		if ($this->getPrimaryKeyFieldName() == $field)
			return false;

		$relations = $this->getTable()->getRelations();
		foreach ($relations as $relation) {
			if ($relation->getLocalFieldName() == $field) {
				return $relation;
			}
		}
		return false;
	}
	/**
	 * public for testing only.
	 * @return Doctrine_Relation
	 */
	public function inverseRelation(Doctrine_Relation $thisRelation)
	{
		if ($thisRelation) {
			$relationships = $thisRelation->getTable()->getRelations();
			foreach ($relationships as $relation) {
				if ($relation->getClass() == get_class($this)) {
					return $relation;
				}
			}
		}
	}
	/**
	 * Loads the record with values from the database, based on $id.
	 *
	 * @param <type> $id
	 * @return <type>
	 * @deprecated
	 */
	public function load($id = null)
	{
		// Doctrine_Record also has a function called load, so these call it
		// when it is intended.
		if (is_array($id))
			return parent::load($id);
		if ($id == null)
			return parent::load();

		$loadedCopy = $this->getTable()->find($id);

		// All of these steps are in effort to make the object save with the
		// same primary key, not as a new record.
		$this->fromArray($loadedCopy->toArray());
		$this->assignIdentifier($loadedCopy->identifier());
		$id_field = $this->getPrimaryKeyFieldName();
		$this->$id_field = $loadedCopy->$id_field;
		$this->state(Doctrine_Record::STATE_CLEAN);

		// I'm not exactly sure why, but this is necessary sometimes, probably
		// if the record had been loaded into Doctrine previously.
		$this->refreshRelated();

		return true;
	}
  public function saveAfterApplyingBusinessRules()
  {
    return $this->save();
  }
	public function save()
	{
		if (self::$disableSave)
			throw new Exception('Save has been disabled.  That means this page '
					. 'should not be calling save().');
		$this->addCreationInfo();
		parent::save();
		return true;
	}
	public function check()
	{
		return true;
	}
	public function checkWithExceptionOnFailure()
	{
		if (!$this->check())
			throw new A25_Exception_DataConstraint($this->_error);
    }
	/**
	 * @deprecated - use save() instead
	 */
	public function store()
	{
		return $this->save();
	}
	/**
	 * @deprecated - use save() instead
	 */
	public function storeWithExceptionOnFailure()
	{
		$this->save();
	}

	public function checkAndStore()
	{
		$this->checkWithExceptionOnFailure();
		$this->saveAfterApplyingBusinessRules();
	}

	/**
	 * Public for testing only.
	 */
	public function addCreationInfo()
	{
		if (isset($this['created']))
			if ($this->created == 0) {
				$this->created = date( 'Y-m-d H:i:s' );
				$this->created_by =
					$this->created_by ? $this->created_by : A25_DI::UserId();
			}

    if ($this->isModified()) {
      if (isset($this['modified']))
        $this['modified'] = date( 'Y-m-d H:i:s' );

      if (isset($this['modified_by']))
        $this['modified_by'] = A25_DI::UserId();
    }
	}

	public function getTableName()
	{
		return $this->getTable()->getTableName();
	}

	public function getPrimaryKeyFieldName()
	{
		return $this->getTable()->getIdentifier();
	}

	/**
	 * Doctrine_Record provides toArray(), but it does not include properties
	 * created via Doctrine_Record->hasAccessorMutator().  This is the same as
	 * toArray(), except it also includes those properties.
	 *
	 * This function does not do a "deep" load of the array.  It does not
	 * bother to include all of the relatives and their record values.  It just
	 * loads the values of the record at hand.
	 *
	 * Originally, we tried overriding toArray() itself, but including accessors
	 * in that messed up the initial hydration of the object.
	 */
	public function toArrayIncludingAccessors()
	{
		$array = parent::toArray(false);

		$componentName = $this->_table->getComponentName();
		if (self::$_customAccessors[$componentName]) {
			foreach (self::$_customAccessors[$componentName] as $key => $accessor) {
				$array[$key] = $this->$accessor();
			}
		}

		return $array;
	}

	/**
	 * Doctrine_Record provides toArray(), but it increments the primary key.
	 * This function leaves the primary key with the same value.
	 */
	public function toArrayWithSameId()
	{
		$array = $this->toArrayIncludingAccessors();
		$key = $this->getPrimaryKeyFieldName();
		$array[$key] = $this->$key;
		return $array;
	}

	/**
	 * This is for subclasses which implement ISelectable.
	 */
	public function getSelectionValue()
	{
		$key = $this->getTable()->getIdentifier();
		return $this->$key;
	}

	public function setProperty($field, $value)
	{
		$this->set($field, $value);
	}

	/**
	 * Some of the old code used bind(), so we have to include it here for a
	 * while.
	 *
	 * @param <type> $array
	 * @return <type>
	 * @deprecated
	 */
	public function bind($array, $ignore='')
	{
		foreach ($array as $key => $value)
		{
			try {
				if (strpos( $ignore, $k) === false) {
					$this->$key = $value;
          if ($this->$key == 'on')
            $this->$key = true;
				}
			} catch (Doctrine_Record_UnknownPropertyException $e) {
				// Do nothing, this is expected sometimes.
			}
		}
		return true;
	}

	/**
	 * Some of the old code used checkin(), so we have to include it here for a
	 * while.	 *
	 * @deprecated
	 */
	public function checkout($user_id, $oid=null)
	{
		try {
			$this->checked_out = 1;
			$this->checked_out_time = date( 'Y-m-d H:i:s' );
			$this->save();
		} catch (Doctrine_Record_UnknownPropertyException $e) {
			// Do nothing, this is expected sometimes.
		}
		return true;
	}

	/**
	 * Some of the old code used checkin(), so we have to include it here for a
	 * while.	 *
	 * @deprecated
	 */
	public function checkin($oid=null)
	{
		try {
			$this->checked_out = 0;
			$this->checked_out_time = '0000-00-00 00:00:00';
			$this->save();
		} catch (Doctrine_Record_UnknownPropertyException $e) {
			// Do nothing, this is expected sometimes.
		}
		return true;
	}
	/**
	 *
	 * @return string
	 * @deprecated
	 */
	public function getError() {
		return $this->_error;
	}

  protected function updateCalculatedValue($field_name, $calculation_function_name)
  {
    if ($this[$field_name] != $this->$calculation_function_name())
      $this->$field_name = $this->$calculation_function_name();
  }
}
