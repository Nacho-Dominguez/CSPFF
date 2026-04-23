<?php
require_once(dirname(__FILE__) . '/../../../autoload.php');

class test_unit_A25_CookieMonsterTest extends test_Framework_UnitTestCase
{
	public function test_sessionCookieName()
	{
		$this->assertEquals(md5('site' . ServerConfig::httpUrlWithoutSlash()),
			A25_CookieMonster::sessionCookieName());
	}
}

?>
