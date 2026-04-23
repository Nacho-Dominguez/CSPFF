<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class A25_InfoPage
{
	private $taskname;
	private $title;
	private $text;
	private $active;

	function __construct($taskname,$title,$text)
	{
		$this->taskname = $taskname;
		$this->title = $title;
		$this->text = $text;
		$this->active = false;
	}
	public function getTaskName()
	{
		return $this->taskname;
	}
	public function getTitle()
	{
		return $this->title;
	}
	public function getText()
	{
		return $this->text;
	}
	public function isActive()
	{
		return $this->active;
	}
	public function makeActive()
	{
		$this->active = true;
	}
}
?>
