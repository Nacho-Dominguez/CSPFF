<?php
require_once(dirname(__FILE__) . '/../../../autoload.php');

class test_unit_A25_RedirectorTest_Redirector extends A25_Redirector
{
	public function prefix($url, $requestUri)
	{
		return parent::prefix($url, $requestUri);
	}
}
class test_unit_A25_RedirectorTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function prefix_doesNothingIfUrlHasHttp()
	{
		$redirector = new test_unit_A25_RedirectorTest_Redirector();
		$this->assertEquals('http://test.com',
			$redirector->prefix('http://test.com','http://other.com/content/'));
	}
	/**
	 * @test
	 */
	public function prefix_prependsExistingURIWithoutSEFPathToContent()
	{
		$redirector = new test_unit_A25_RedirectorTest_Redirector();
		$this->assertEquals('http://test.com/index.php',
			$redirector->prefix('index.php','http://test.com/content/view/'));
	}
	/**
	 * @test
	 */
	public function prefix_prependsExistingURIWithoutSEFPathToComponent()
	{
		$redirector = new test_unit_A25_RedirectorTest_Redirector();
		$this->assertEquals('http://test.com/index.php',
			$redirector->prefix('index.php','http://test.com/component/student/'));
	}
	/**
	 * @test
	 */
	public function prefix_prependsExistingURIWithoutPHPFile()
	{
		$redirector = new test_unit_A25_RedirectorTest_Redirector();
		$this->assertEquals('http://test.com/index.php',
			$redirector->prefix('index.php','http://test.com/other.php'));
	}
}
?>
