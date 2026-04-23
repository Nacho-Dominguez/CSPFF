<?php

class Controller_Administrator_Documentation_Coupons extends Controller
{
	public function executeTask()
	{
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to view this.");
		require dirname(__FILE__) . '/Coupons.phtml';
	}
}


