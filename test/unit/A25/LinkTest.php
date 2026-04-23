<?php
require_once(dirname(__FILE__) . '/../../../autoload.php');

class test_unit_A25_LinkTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function removeDoubleSlashes_worksWithHttp()
	{
		$this->assertEquals('http://test.com/administrator/index.php',
			A25_Link::removeDoubleSlashes('http://test.com//administrator///index.php')
		);
	}
	/**
	 * @test
	 */
	public function removeDoubleSlashes_worksWithHttps()
	{
		$this->assertEquals('https://test.com/administrator/index.php',
			A25_Link::removeDoubleSlashes('https://test.com//administrator///index.php')
		);
	}
	/**
	 * @test
	 */
	public function convertToSEF_skipsAdministrator()
	{
		$this->assertEquals('/administrator/index2.php?option=com_student',
			A25_Link::convertToSEF('/administrator/index2.php?option=com_student')
		);
	}
	/**
	 * @test
	 */
	public function convertToSEF_changesComponent()
	{
		$this->assertEquals('/component/option,com_course/task,new/Itemid,19/',
			A25_Link::convertToSEF('/index.php?option=com_course&task=new&Itemid=19')
		);
	}
	/**
	 * @test
	 */
	public function convertToSEF_changesContent()
	{
		$this->assertEquals('/content/view/27/42/',
			A25_Link::convertToSEF('/index.php?option=com_content&task=view&id=27&Itemid=42')
		);
	}
	/**
	 * @test
	 */
	public function convertToSEF_handlesHtmlAmpersand()
	{
		$this->assertEquals('/component/option,com_course/task,new/Itemid,19/',
			A25_Link::convertToSEF('/index.php?option=com_course&amp;task=new&amp;Itemid=19')
		);
	}
	/**
	 * @test
	 */
	public function convertToSEF_worksWithEmptyArguments()
	{
		$this->assertEquals('/component/option,com_course/empty,/Itemid,19/',
			A25_Link::convertToSEF('/index.php?option=com_course&empty=&Itemid=19')
		);
	}
}
?>
