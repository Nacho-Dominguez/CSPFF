<?php

class A25_Page_StateSelector
{
	private $state;
	private $thisPath;
	private $forwardPath;

	public function __construct($state, $thisPath, $forwardPath)
	{
		$this->state = $state;
		$this->thisPath = $thisPath;
		$this->forwardPath = $forwardPath;
	}

	public function forwardOrList()
	{
		$this->interactWithCookie();
		$this->redirectIfAppropriate();
		$this->displayList();
	}

	private function interactWithCookie()
	{
		$cookieName = 'state' . A25_CookieMonster::sessionCookieName();
		if ($this->state)
			A25_CookieMonster::setSitewideCookie($cookieName, $this->state);
		else
			$this->state = $_COOKIE[$cookieName];
	}
	/**
	 * Protected for testing only.
	 */
	protected function redirectIfAppropriate()
	{
		// 'none' is used to reset, if wanting to select another state
		if (!$this->state || $this->state == 'none')
			return;

		$redirector = A25_DI::Redirector();

		$redirector->redirect('/' . strtolower($this->state) . '/'
				. $this->forwardPath);
	}

	private function displayList()
	{
		echo '<p>Please select your state:</p>';
		echo '<ul>';
		echo $this->listItem('California', 'CA');
		echo $this->listItem('Colorado', 'CO');
		echo $this->listItem('Idaho', 'ID');
		echo $this->listItem('Kentucky', 'KY');
		echo $this->listItem('Maine', 'Maine');
		echo $this->listItem('Wyoming', 'WY');
		echo '</ul>';
	}
	private function listItem($stateName, $stateAbbrev)
	{
		return '<li><a href=' . A25_Link::to($this->thisPath . $stateAbbrev . '/')
				. ">$stateName</a></li>";
	}
}

?>
