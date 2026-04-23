<?php

/**
 * @property integer $agency_id
 * @property string $name
 */
class A25_Record_Agency extends A25_DoctrineRecord
{
  public function setTableDefinition()
  {
      $this->setTableName('agency');
      $this->hasColumn('agency_id', 'integer', 2, array(
           'type' => 'integer',
           'length' => 2,
           'fixed' => false,
           'unsigned' => false,
           'primary' => true,
           'autoincrement' => true,
           ));
      $this->hasColumn('name', 'string', 255, array(
           'type' => 'string',
           'length' => 255,
           'fixed' => false,
           'unsigned' => false,
           'primary' => false,
           'default' => '',
           'notnull' => true,
           'autoincrement' => false,
           ));
  }
	/**
	 * @param integer $id
	 * @return A25_Record_Agency
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_Agency')->find($id);
  }
	public function getSelectionName() {
		return $this->name;
	}
}