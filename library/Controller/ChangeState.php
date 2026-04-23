<?php

class Controller_ChangeState extends Controller
{
	public function executeTask()
	{
		$cookieName = 'state' . A25_CookieMonster::sessionCookieName();
    setcookie($cookieName, '', time() - 3600, '/');
    
    A25_DI::Redirector()->redirectBasedOnSiteRoot('');
	}
}
