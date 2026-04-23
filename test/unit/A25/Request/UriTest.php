<?php

class test_unit_A25_Request_UriTest extends test_Framework_UnitTestCase
{
  private $server;
  public function setUp()
  {
    parent::setUp();
    $this->server = A25_DI::ServerConfig();
    $this->server->httpUrl = 'http://aliveat25.us/co/';
    $this->server->httpsUrl = 'https://aliveat25.us/co/';
  }
	/**
	 * @test
	 */
	public function excludesHttp()
	{
		$task = 'find-a-course';
    $fullUrl = 'http://aliveat25.us/co/' . $task;
    $request = $this->getMock('A25_Request', array('fullUrl'));
    $request->expects($this->once())->method('fullUrl')->will($this->returnValue($fullUrl));
		$this->assertEquals('/' . $task, $request->uri());
	}
	/**
	 * @test
	 */
	public function excludesHttps()
	{
		$task = 'find-a-course';
    $fullUrl = 'https://aliveat25.us/co/' . $task;
    $request = $this->getMock('A25_Request', array('fullUrl'));
    $request->expects($this->once())->method('fullUrl')->will($this->returnValue($fullUrl));
		$this->assertEquals('/' . $task, $request->uri());
	}
	/**
	 * @test
	 */
	public function excludesHttpWithPort()
	{
    $this->server->httpUrl = 'http://aliveat25.us:8080/co/';
		$task = 'find-a-course';
    $fullUrl = 'http://aliveat25.us:8080/co/' . $task;
    $request = $this->getMock('A25_Request', array('fullUrl'));
    $request->expects($this->once())->method('fullUrl')->will($this->returnValue($fullUrl));
		$this->assertEquals('/' . $task, $request->uri());
	}
	/**
	 * @test
	 */
	public function excludesHttpsWithPort()
	{
    $this->server->httpsUrl = 'https://aliveat25.us:8080/co/';
		$task = 'find-a-course';
    $fullUrl = 'https://aliveat25.us:8080/co/' . $task;
    $request = $this->getMock('A25_Request', array('fullUrl'));
    $request->expects($this->once())->method('fullUrl')->will($this->returnValue($fullUrl));
		$this->assertEquals('/' . $task, $request->uri());
	}
	/**
	 * @test
	 */
	public function excludesAdod()
	{
    $this->server->httpUrl = 'http://adod.coloradosafedriver.org/';
		$task = 'find-a-course';
    $fullUrl = 'http://adod.coloradosafedriver.org/' . $task;
    $request = $this->getMock('A25_Request', array('fullUrl'));
    $request->expects($this->once())->method('fullUrl')->will($this->returnValue($fullUrl));
		$this->assertEquals('/' . $task, $request->uri());
	}
	/**
	 * @test
	 */
	public function excludesSlashIfAlreadySlash()
	{
		$task = '/find-a-course';
    $fullUrl = 'http://aliveat25.us/co/' . $task;
    $request = $this->getMock('A25_Request', array('fullUrl'));
    $request->expects($this->once())->method('fullUrl')->will($this->returnValue($fullUrl));
		$this->assertEquals($task, $request->uri());
	}
	/**
	 * @test
	 */
	public function includesSlashIfNoSlash()
	{
    $this->server->httpUrl = 'http://aliveat25.us/co';
		$task = 'find-a-course';
    $fullUrl = 'http://aliveat25.us/co/' . $task;
    $request = $this->getMock('A25_Request', array('fullUrl'));
    $request->expects($this->once())->method('fullUrl')->will($this->returnValue($fullUrl));
		$this->assertEquals('/' . $task, $request->uri());
	}
}
