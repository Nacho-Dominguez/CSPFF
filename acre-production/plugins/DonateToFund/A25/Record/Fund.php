<?php

class A25_Record_Fund extends A25_DoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('fund');
        $this->hasColumn('fund_id', 'integer', 4, array(
            'type' => 'integer',
            'length' => 4,
            'fixed' => false,
            'unsigned' => true,
            'primary' => true,
            'autoincrement' => true,
        ));
        $this->hasColumn('name', 'string', 255, array(
            'type' => 'string',
            'length' => 255,
            'fixed' => false,
            'unsigned' => false,
            'primary' => false,
            'notnull' => false,
            'autoincrement' => false,
        ));
        $this->hasColumn('is_active', 'integer', 1, array(
            'type' => 'integer',
            'length' => 1,
            'fixed' => false,
            'unsigned' => true,
            'primary' => false,
            'default' => 0
        ));
    }

    public static function retrieve($id)
    {
        return Doctrine::getTable('A25_Record_Fund')->find($id);
    }

    public function getSelectionName()
    {
        return $this->name;
    }
}
