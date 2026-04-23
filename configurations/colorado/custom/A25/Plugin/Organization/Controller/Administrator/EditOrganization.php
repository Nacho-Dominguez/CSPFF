<?php

class Controller_Administrator_EditOrganization extends Controller
{
	public function executeTask()
	{
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to view this.");
    
		$id = $this->request['id'];
		$organization =  new JosOrganization();
		
		if ($id > 0) {
			$organization = Doctrine::getTable('JosOrganization')->find($id);
		}

		$form = new A25_Plugin_Organization_Form($organization, 'task=ViewOrganization&id=' . $id);
		$form->run($_POST);
	}
}
