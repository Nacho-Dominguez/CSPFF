<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Court_GetSurchargeFeeTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function getCourtSurcharge_whenCourtHasASurchargeFeeSet()
	{
		$court = new A25_Record_Court();
		$court->surcharge_fee = 5;

		$this->assertEquals(5,$court->getSurchargeFee());
	}
	/**
	 * @test
	 */
	public function getPlatformConfigSurcharge_whenSurchargeFeeNotSet()
	{
		$court = new A25_Record_Court();
		$court->surcharge_fee = null;

		$this->assertEquals(PlatformConfig::defaultCourtSurcharge,
				$court->getSurchargeFee());
	}
	/**
	 *
	 * @test
	 */
	public function getCourtSurcharge_whenSurchargeFeeIsSetToZero()
	{
		$court = new A25_Record_Court();
		$court->surcharge_fee = 0;

		$this->assertEquals(0, $court->getSurchargeFee());
	}
}
?>