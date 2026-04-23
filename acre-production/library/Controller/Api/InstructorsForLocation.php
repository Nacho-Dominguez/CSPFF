<?php

class Controller_Api_InstructorsForLocation extends Controller
{
	public function executeTask()
	{
		A25_DoctrineRecord::$disableSave = true;
		
		$location_id = intval($_GET['id']);
		if ($location_id)
			$location = A25_Record_Location::retrieve($location_id);
		
		$instructor_id = new A25_Form_Element_Select_Instructor('instructor_id',
				$location);
        $instructor_id->setRequired(false)
				->setLabel('Instructor 1');
		$instructor_id->setDecorators(array(
				'ViewHelper',
				'Errors',
				array('Description', array('tag' => 'span'))
		));
		
		$instructor_2_id = new A25_Form_Element_Select_Instructor('instructor_2_id',
				$location);
        $instructor_2_id->setRequired(false)
				->setLabel('Instructor 1');
		$instructor_2_id->setDecorators(array(
				'ViewHelper',
				'Errors',
				array('Description', array('tag' => 'span'))
		));
		
		$view = new Zend_View();
		echo $instructor_id->render($view) . ' & '
				. $instructor_2_id->render($view);
	}
}


