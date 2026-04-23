<?php

class Controller_Administrator_Documentation_Agency extends Controller
{
	public function executeTask()
	{
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to view this.");
    A25_DI::HtmlHead()->append('<link href="'
        . A25_Link::to('/templates/aliveat25/css/bootstrap.css')
        . '" rel="stylesheet" media="screen" />
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">');
    A25_DI::HtmlHead()->append('<style type="text/css">.carousel-caption{color: inherit; text-shadow: inherit;}</style>');
    A25_DI::HtmlHead()->includeJquery();
		require dirname(__FILE__) . '/Agency.phtml';
	}
}


