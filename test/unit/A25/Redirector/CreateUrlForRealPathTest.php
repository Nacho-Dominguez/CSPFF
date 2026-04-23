<?php

class test_unit_A25_Redirector_CreateUrlForRealPathTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function redirectsOnFrontEnd()
	{
    $_SERVER['REQUEST_URI'] = ServerConfig::staticHttpUrl() . 'another-controller';
		$redirector = new RedirectorWithCreateUrlForRealPathExposed();
		$this->assertEquals(ServerConfig::currentUrl() . '/controller',
			$redirector->createUrlForRealPath('/controller'));
	}
	/**
	 * @test
	 */
	public function redirectsOnBackEnd()
	{
    $_SERVER['REQUEST_URI'] = ServerConfig::staticHttpUrl() . 'administrator/another-controller';
		$redirector = new RedirectorWithCreateUrlForRealPathExposed();
		$this->assertEquals(ServerConfig::currentUrl() . '/administrator//controller',
			$redirector->createUrlForRealPath('/controller'));
	}
}

class RedirectorWithCreateUrlForRealPathExposed extends A25_Redirector
{
	public function createUrlForRealPath($url)
	{
		return parent::createUrlForRealPath($url);
	}
}
