<?php

/**
 * @property integer $trainee_id
 * @property integer $trainer_id
 */
class A25_Record_InstructorTrainer extends A25_DoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('instructor_trainer');
        $this->hasColumn('trainee_user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('trainer_user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }
}