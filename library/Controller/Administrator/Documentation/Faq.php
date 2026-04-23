<?php

class Controller_Administrator_Documentation_Faq extends Controller
{
	public function executeTask()
	{
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to view this.");
    
    A25_DI::HtmlHead()->append('<link href="'
        . A25_Link::to('/templates/aliveat25/css/bootstrap.css')
        . '" rel="stylesheet" media="screen" />');
    
		require dirname(__FILE__) . '/Faq.phtml';
	}
}


