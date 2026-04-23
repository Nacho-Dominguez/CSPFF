<?php

class test_unit_A25_PageNav_LinkStartingAtTest extends
		test_Framework_UnitTestCase
{
	public function setUp()
	{
		$_SERVER['REQUEST_URI'] = null;
		parent::setUp();
	}
	public function tearDown()
	{
		$_SERVER['REQUEST_URI'] = null;
	}

    /**
	 * @test
	 */
	public function appendsStartToQuerystring()
	{
		$_SERVER['REQUEST_URI'] = '/co/find-a-course';

		$this->assertEquals('/co/find-a-course?start=20',
				unit_LinkStartingAt_PageNav::linkStartingAt(20));
    }
    /**
	 * @test
	 */
	public function replacesStartAfterQuestionMark()
	{
		$_SERVER['REQUEST_URI'] = '/co/find-a-course?start=10';

		$this->assertEquals('/co/find-a-course?start=20',
				unit_LinkStartingAt_PageNav::linkStartingAt(20));
    }
    /**
	 * @test
	 */
	public function replacesStartAfterAmpersand()
	{
		$_SERVER['REQUEST_URI'] = '/co/find-a-course?zip=80401&start=10';

		$this->assertEquals('/co/find-a-course?zip=80401&amp;start=20',
				unit_LinkStartingAt_PageNav::linkStartingAt(20));
    }
    /**
	 * @test
	 */
	public function skipsStartAsPartOfOtherArgument()
	{
		$_SERVER['REQUEST_URI'] = '/co/find-a-course?zip=80401&limitstart=10';

		$this->assertEquals('/co/find-a-course?zip=80401&amp;limitstart=10&amp;start=20',
				unit_LinkStartingAt_PageNav::linkStartingAt(20));
    }
}

class unit_LinkStartingAt_PageNav extends A25_PageNav
{
	public static function linkStartingAt($start)
	{
		return parent::linkStartingAt($start);
	}
}