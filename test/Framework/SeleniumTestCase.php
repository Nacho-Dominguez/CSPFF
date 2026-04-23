<?php

abstract class test_Framework_SeleniumTestCase extends
		AppDevl\Selenium2Adapter\Selenium2WithAdapterTestCase
{
	/**
	 * $name is required for the constructor of test_Framework_TestCase
	 */
	function __construct($name = NULL) {
		parent::__construct($name);
	}

	/**
	 * NOTE: Don't add database reset code to this!  The live tests use it.
	 */
	public function setUp() {
		$this->setHost(ServerConfig::seleniumHost);
		$this->setPort((int) ServerConfig::seleniumPort);
		$this->setBrowser('chrome');
		$this->setBrowserUrl(ServerConfig::staticHttpUrl());
        $this->setDesiredCapabilities(['acceptSslCerts' => true]);
	}

	/**
	 * It was necessary to overide this function for
	 * thomasalbright.com/aliveat25/ to work.
	 *
	 * @param <type> $path
	 */
	public function openRelative($path) {
        $this->open(A25_Link::https($path));
	}

	protected function assertHtmlSourceMatch($pattern, $message=null)
	{
		$this->assertTrue((bool)preg_match($pattern,$this->getHtmlSource()),
				$message);
    }

	protected function confirmTheConfirmation()
	{
		$this->acceptAlert(); // Clicks 'yes' on the pop-up window
		$this->waitForPageToLoad();
		sleep(1);
    }
}
?>
