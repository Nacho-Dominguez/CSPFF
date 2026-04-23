<?php

class A25_Page_ProgramInfo
{
	private $taskname;
	private $aboutUs;
    private $whyThisWorks;
    private $courses;
	private $registrationGuidelines;
	private $paymentPolicies;
	private $supportUs;
    private $curriculum;
    private $evidence;
    private $resources;
    private $cert;
    private $whyNSC;
    private $diversity;

	public function __construct($taskname, A25_InfoPage $aboutUs, A25_InfoPage $whyThisWorks,
            A25_InfoPage $courses, A25_InfoPage $registrationGuidelines,
			A25_InfoPage $paymentPolicies, A25_InfoPage $supportUs,
            A25_InfoPage $curriculum, A25_InfoPage $evidence,
            A25_InfoPage $resources, A25_InfoPage $cert, A25_InfoPage $whyNSC, A25_InfoPage $diversity)
	{
		$this->taskname = $taskname;
		$this->aboutUs = $aboutUs;
        $this->whyThisWorks = $whyThisWorks;
        $this->courses = $courses;
		$this->registrationGuidelines = $registrationGuidelines;
		$this->paymentPolicies = $paymentPolicies;
		$this->supportUs = $supportUs;
        $this->curriculum = $curriculum;
        $this->evidence = $evidence;
        $this->resources = $resources;
        $this->cert = $cert;
        $this->whyNSC = $whyNSC;
        $this->diversity = $diversity;
	}

	public function display()
	{
		$activePage = $this->getActivePageAndDisplayMenu();
	    ?>
		<div class="locationContent">
		    <div class="colHeader"><? echo $activePage->getTitle() ?><br /></div>
		    <div id="colContent" style="min-height: 0px;">
				<?php echo $activePage->getText() ?>
		    </div>
	    </div>
	    <?php
	}
	private function getActivePageAndDisplayMenu()
	{
        $infoPages = array($this->aboutUs, $this->whyThisWorks, $this->courses,
            $this->registrationGuidelines, $this->paymentPolicies,
            $this->supportUs, $this->curriculum, $this->evidence,
            $this->resources, $this->cert, $this->whyNSC, $this->diversity);

		echo '<div id="mainlevel-nav">';

		foreach ($infoPages as $page) {
			if ($page->getTaskName() == $this->taskname) {
				$page->makeActive();
				$activePage = $page;
			}
			if ($page->getText() != '')
				echo self::menuLink($page);
		}

	    echo '<div><a href="' . A25_Link::to('/')
                . '" class="mainlevel-nav">Sign Up</a></div>
            </div>
		<div class="clr"></div>';
		return $activePage;
	}
	static private function menuLink(A25_InfoPage $page)
	{
		$html = '<div><a href="'
			  . A25_Link::to('/index.php?option=com_location&task='
					. $page->getTaskName() . '&Itemid=42')
			  . '" class="mainlevel-nav"';
		if($page->isActive())
			$html .= ' id="active_menu-nav"';
		$html .= '>'.$page->getTitle().'</a></div>';
		return $html;
	}
}

?>
