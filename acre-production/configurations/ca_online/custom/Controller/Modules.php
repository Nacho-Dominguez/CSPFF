<?php

/**
 * @todoSomeday - unit test this class
 */
class Controller_Modules extends Controller
{
	public function executeTask()
	{
		require dirname(__FILE__) . '/Modules.phtml';
	}
}
