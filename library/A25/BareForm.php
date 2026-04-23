<?php

abstract class A25_BareForm extends Zend_Form
{
  /**
   * When the save() function returns $this->successMessage, the user will be
   * redirected to $this->_redirectToQuerystring.
   */
	public $successMessage = 'Saved';

	public function run($data)
	{
		$statusMessage = $this->populateAndSaveIfNecessary($data);

		// Redirect if save was successful:
		if ($statusMessage == $this->successMessage)
			$this->redirect();

		$this->display($statusMessage);
	}

	abstract protected function redirect();
	
	protected function display($statusMessage)
	{
		$view = new Zend_View();
		$view->addHelperPath('A25/ViewHelper', 'A25_ViewHelper');
		$this->setView($view);

		echo $statusMessage;
		echo $this;
	}
	
	protected function populateAndSaveIfNecessary($data)
	{
		if ($data) {
			$this->populate($data);
			$statusMessage = $this->validateAndSave($data);
		}
		return $statusMessage;
	}
	
	protected function validateAndSave($data)
	{
		if($this->isValid($data)) {
			return $this->save();
		}
	}

  /**
   * When save is successful, it should return $this->statusMessage. When
   * unsuccessful, return any other message, and instead of redirecting to the
   * success page, the form will be displayed again with the message at the top.
   */
	abstract protected function save();
}