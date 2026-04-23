<?php
class RedirectIfAppropriateTest_unit_A25_Page_StateSelector
		extends A25_Page_StateSelector
{
	public function redirectIfAppropriate()
	{
		return parent::redirectIfAppropriate();
	}
}

class test_unit_A25_StateSelector_RedirectIfAppropriateTest extends
		test_Framework_UnitTestCase
{
	private $redirector;
	public function setUp()
	{
		parent::setUp();

		$this->redirector = $this->mock('A25_Redirector');
		A25_DI::setRedirector($this->redirector);
	}
	/**
	 * @test
	 */
	public function doesNothingWithNone()
	{
		$selector = new RedirectIfAppropriateTest_unit_A25_Page_StateSelector('none', 'thisPath', 'forwardPath');

		$this->redirector->expects($this->never())->method('redirect');

		$selector->redirectIfAppropriate();
	}
	/**
	 * @test
	 */
	public function doesNothingWithEmptyString()
	{
		$selector = new RedirectIfAppropriateTest_unit_A25_Page_StateSelector('', 'thisPath', 'forwardPath');

		$this->redirector->expects($this->never())->method('redirect');

		$selector->redirectIfAppropriate();
	}
	/**
	 * @test
	 */
	public function worksForColorado()
	{
		$selector = new RedirectIfAppropriateTest_unit_A25_Page_StateSelector('CO', 'thisPath', 'forwardPath');

		$this->redirector->expects($this->once())->method('redirect')
			->with('/co/forwardPath');
		
		$selector->redirectIfAppropriate();
	}
	/**
	 * @test
	 */
	public function worksForCalifornia()
	{
		$selector = new RedirectIfAppropriateTest_unit_A25_Page_StateSelector('CA', 'thisPath', 'forwardPath');

		$this->redirector->expects($this->once())->method('redirect')
			->with('/ca/forwardPath');

		$selector->redirectIfAppropriate();
	}
	/**
	 * @test
	 */
	public function worksForIdaho()
	{
		$selector = new RedirectIfAppropriateTest_unit_A25_Page_StateSelector('ID', 'thisPath', 'forwardPath');

		$this->redirector->expects($this->once())->method('redirect')
			->with('/id/forwardPath');

		$selector->redirectIfAppropriate();
	}
	/**
	 * @test
	 */
	public function worksForNorthDakota()
	{
		$selector = new RedirectIfAppropriateTest_unit_A25_Page_StateSelector('ND', 'thisPath', 'forwardPath');

		$this->redirector->expects($this->once())->method('redirect')
			->with('/nd/forwardPath');

		$selector->redirectIfAppropriate();
	}
	/**
	 * @test
	 */
	public function worksForWyoming()
	{
		$selector = new RedirectIfAppropriateTest_unit_A25_Page_StateSelector('WY', 'thisPath', 'forwardPath');

		$this->redirector->expects($this->once())->method('redirect')
			->with('/wy/forwardPath');

		$selector->redirectIfAppropriate();
	}
}
