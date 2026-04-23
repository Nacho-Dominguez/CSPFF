<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_Trainees extends A25_Filter
{
	protected $instructor_ids;
	
  // @todo-soon - Remove duplication with A25_Filter_Instructor->modifyQuery().
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->instructor_ids) {
			$ids_string = implode(',',$this->instructor_ids);
			$q->andWhere('(c.instructor_id IN (' . $ids_string . 
					') OR c.instructor_2_id IN (' . $ids_string . '))');
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Instructor';
	}
	
	protected function field()
	{
		// @todoToday - remove duplication in creating csv string
		// Unfortunately, we cannot use Doctrine's built-in support of non-equal
		// nest relations such as this one due to a bug filed at
		// http://www.doctrine-project.org/jira/browse/DC-952.  Instead, for now,
		// we have to query the InstructorTrainer table individually in order to
		// get the trainees:
		$trainee_ids = array();
		$trainees = Doctrine_Query::create()
			->from('A25_Record_InstructorTrainer t')
			->where('t.trainer_user_id = ?', A25_DI::UserId())->execute();
		
		foreach ($trainees as $trainee) {
			$trainee_ids[] = $trainee->trainee_user_id;
		}
		
		$q = Doctrine_Query::create()->select()
			->from('A25_Record_User u')
			->whereIn('u.id', $trainee_ids);
		
		if (!count($trainee_ids))
			$q->andWhere('1 = 0');
		
		return $this->generateMultiSelect('instructor_ids', 'A25_Record_User',
				'name', $q);
	}
}