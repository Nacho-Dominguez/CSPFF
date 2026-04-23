<?php

abstract class Controller
{
	/**
	 * This exists only for testing.  Normally, it will just be $_REQUEST, but
	 * in testing, it can be something else.  However, using this does
	 * potentially allow for switching POST and GET, which is (allegedly) a
	 * security risk.  It might be more secure to separate into $this->get and
	 * $this->post, but I'm not yet convinced that is actually a security risk.
	 *
	 * @var associative array
	 */
	protected $request;

	public function __construct($request)
	{
		$this->request = $request;
		$this->setTitle();
	}

	abstract public function executeTask();
	
	private function setTitle()
	{
		/**
		 * @var mosMainFrame $mainframe
		 */
		global $mainframe;
		
		$title = html_entity_decode(PlatformConfig::siteTitleHtml());
		
		$subtitle = $this->subtitle();
		if ($subtitle)
			$title .= ': ' . $subtitle;
		
		if ($mainframe)
			$mainframe->setPageTitleAlias($title);
	}
	
	/**
	 * Override this in the child class to give the page a subtitle.
	 * 
	 * @return string or null
	 */
	protected function subtitle()
	{
		return null;
	}
}
