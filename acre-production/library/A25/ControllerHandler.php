<?php

class A25_ControllerHandler extends A25_StrictObject
{
	protected $task;

	public function __construct($task)
	{
		$this->task = $task;
	}

	public function loadController()
	{
		$controllerClassName = $this->generateClassName();

		try {
			if (!class_exists($controllerClassName)
					|| !is_subclass_of($controllerClassName, 'Controller'))
				return false;
		} catch(Exception $e) {
			// Most likely the class does not exist
			return false;
		}

		$controller = new $controllerClassName($_REQUEST);

		$controller->executeTask();
		return true;
	}

	protected function generateClassName()
	{
		return 'Controller_' . $this->task;
	}
}