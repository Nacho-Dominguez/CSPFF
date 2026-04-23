<?php

/**
 * @todoSomeday - unit test this class
 */
class Controller_Administrator_HideBroadcast extends Controller
{
	public function executeTask()
	{
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to do this.");

		$broadcast_id = (int)$_POST['broadcast_id'];
		if ($broadcast_id < 1)
			exit("You must specify a broadcast id");

		$hide = new HideBroadcast();
		$hide->user_id = $user_id;
		$hide->broadcast_id = $broadcast_id;
		$hide->save();
	}
}


