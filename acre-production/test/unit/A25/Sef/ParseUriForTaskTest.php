<?php

class test_unit_A25_Sef_ParseUriForTaskTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function grabsASingleWord()
	{
		$task = 'FindACourse';
		$request_uri = "/$task";
		$this->assertEquals($task, A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function convertsHyphensToCamelCase()
	{
		$request_uri = "/find-a-course";
		$this->assertEquals('FindACourse', A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function grabsASingleDirectory()
	{
		$task = 'FindACourse';
		$request_uri = "/$task/";
		$this->assertEquals($task, A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function grabsWithQueryStringAfter()
	{
		$task = 'FindACourse';
		$request_uri = "/$task?arg1=1&arg2=2";
		$this->assertEquals($task, A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function grabsWithQueryStringAfterSlash()
	{
		$task = 'FindACourse';
		$request_uri = "/$task/?arg1=1&arg2=2";
		$this->assertEquals($task, A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function grabsWithQuestionMarkAfter()
	{
		$task = 'FindACourse';
		$request_uri = "/$task?";
		$this->assertEquals($task, A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function convertsSlashesToUnderscores()
	{
		$request_uri = "/administrator/Dir1/Dir2";
		$this->assertEquals('Administrator_Dir1_Dir2', A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function capitalizesCorrectly()
	{
		$request_uri = "/administrator/find-a-course";
		$this->assertEquals('Administrator_FindACourse', A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function returnsFalseIfOnlySlash()
	{
		$request_uri = "/";
		$this->assertFalse(A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function returnsFalseIfPhpFile()
	{
		$request_uri = '/index.php';
		$this->assertFalse(A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function returnsFalseIfAdministratorRoot()
	{
		$request_uri = '/administrator';
		$this->assertFalse(A25_Sef::parseUriForTask($request_uri));
	}
	/**
	 * @test
	 */
	public function returnsFalseIfAdministratorRootDir()
	{
		$request_uri = '/administrator/';
		$this->assertFalse(A25_Sef::parseUriForTask($request_uri));
	}
}
