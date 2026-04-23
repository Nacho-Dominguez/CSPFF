<?php

class A25_Plugin_InstructorTrainer implements A25_ListenerI_Doctrine,
		A25_ListenerI_AddIcons, A25_ListenerI_AddUserFields,
		A25_ListenerI_AddEmailGroups
{
	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord) 
	{
		if ($doctrineRecord instanceof A25_Record_User) {
			$doctrineRecord->hasColumn('is_a_trainer', 'integer', 1, array(
				'type' => 'integer',
				'length' => 1,
				'fixed' => false,
				'unsigned' => false,
				'primary' => false,
				'default' => '0',
				'notnull' => true,
				'autoincrement' => false,
				));
		}
	}
	public function afterAdminButtons()
	{
		if (!A25_DI::User()->is_a_trainer)
			return;
		
		$link = 'ListCoursesOfTrainees';
		quickiconButton( $link, 'generic.png', 'Manage My Instructors' );
	}
	
	public function afterGroup($row) {
		if (A25_DI::User()->isSuperAdmin()) {
			$trainersCollection = Doctrine_Query::create()->from('A25_Record_User')
					->where('is_a_trainer = ?', true)->execute();
			
			$trainersHash[''] = '(none)';
			foreach ($trainersCollection as $trainer)
				$trainersHash[$trainer->id] = $trainer->name;
			
			$currentTrainerObjects = Doctrine_Query::create()
				->from('A25_Record_InstructorTrainer t')
				->where('t.trainee_user_id = ?', $row->id)->execute();
			foreach ($currentTrainerObjects as $trainer)
				$currentTrainers[] = $trainer->trainer_user_id;
			
			$trainers = new Zend_Form_Element_Multiselect('trainer_ids', array(
				'multiOptions' => $trainersHash,
				'value' => $currentTrainers
			));
			
			$trainers->removeDecorator('label');
			$trainers->removeDecorator('HtmlTag');
			
			?>
				<tr>
					<td>
					Trains Instructors:
					</td>
					<td>
					<?php // This hidden input makes it set the value to 0 when
					      // not checked. ?>
					<input type="hidden" name="is_a_trainer" value="0" />
					<input type="checkbox" name="is_a_trainer" value="1" <?php if ($row->is_a_trainer) echo 'checked'?> />
					</td>
				</tr>
				<tr>
					<td>
					Being trained by:
					</td>
					<td>
					<?php
					echo $trainers->render(new Zend_View());
					?>
					</td>
				</tr>
				<?php
		}
	}
	/**
	 * This is my first attempt at managing a many-to-many relationship with
	 * Doctrine.  Doctrine allegedly has built-in support for non-equal nest
	 * relations such as this one, but I could not get it to work, possibly due
	 * to a bug filed at http://www.doctrine-project.org/jira/browse/DC-952.
	 * 
	 * Next time we have to save a many-to-many relationship, this should be
	 * streamlined along with it to remove duplication and create a new process
	 * which involves fewer steps for many-to-many relationships to follow.
	 */
	public function saveUser($row)
	{
		$new_trainer_ids = $_POST['trainer_ids'];
		if (!$new_trainer_ids || $new_trainer_ids == array(''))
			$new_trainer_ids = array();

		$old_trainers = Doctrine_Query::create()
				->select('trainer_user_id')
				->from('A25_Record_InstructorTrainer')
				->where('trainee_user_id = ?', $row->id)
				->execute();

		$old_trainer_ids = array();

		foreach ($old_trainers as $old_trainer)
			$old_trainer_ids[] = $old_trainer->trainer_user_id;

		$add = array_diff($new_trainer_ids, $old_trainer_ids);
		$del = array_diff($old_trainer_ids, $new_trainer_ids);

		foreach ($add as $add_id) {
			$new_trainer = new A25_Record_InstructorTrainer();
			$new_trainer->trainer_user_id = $add_id;
			$new_trainer->trainee_user_id = $row->id;
			$new_trainer->save();
		}
		if(count($del)) {
			Doctrine_Query::create()
				->delete('A25_Record_InstructorTrainer')
				->where('trainee_user_id = ?', $row->id)
				->andWhereIn('trainer_user_id', $del)->execute();
		}
	}
	
	public function usersForGroup($group_name)
	{
		if ($group_name != 'Instructor Trainer')
			return;
		
		return Doctrine_Query::create()
			->from('A25_Record_User u')
			->where('u.is_a_trainer = ?', true)
			->execute();
	}
	
	public function modifyUsertypes($usertypes)
	{
		array_push($usertypes, 'Instructor Trainer');
		array_multisort($usertypes);
		return $usertypes;
	}
}

set_include_path(
	ServerConfig::webRoot . '/plugins/InstructorTrainer' . PATH_SEPARATOR
	. get_include_path()
);