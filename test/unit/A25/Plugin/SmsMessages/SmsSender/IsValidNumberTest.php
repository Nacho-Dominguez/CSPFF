<?php

require_once ServerConfig::webRoot . '/plugins/SmsMessages/A25/SmsSender.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Plugin_SmsMessages_SmsSender_IsValidNumberTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function isNotValidIfNull()
	{
    $number = NULL;
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(false, $result);
	}

	/**
	 * @test
	 */
	public function isNotValidIfEmpty()
	{
    $number = '';
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(false, $result);
	}

	/**
	 * @test
	 */
	public function isNotValidIfText()
	{
    $number = 'text';
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(false, $result);
	}

	/**
	 * @test
	 */
	public function isNotValidIfNoAreaCode()
	{
    $number = '333-4444';
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(false, $result);
	}

	/**
	 * @test
	 */
	public function isValidIfNoDashes()
	{
    $number = '3334445555';
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(true, $result);
	}

	/**
	 * @test
	 */
	public function isValidIfDots()
	{
    $number = '333.444.5555';
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(true, $result);
	}

	/**
	 * @test
	 */
	public function isValidIfDashes()
	{
    $number = '333-444-5555';
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(true, $result);
	}

	/**
	 * @test
	 */
	public function isValidIfSpaces()
	{
    $number = '333 444 5555';
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(true, $result);
	}

	/**
	 * @test
	 */
	public function isValidIfParentheses()
	{
    $number = '(333) 444 5555';
    $sender = new SmsSenderWithIsValidNumberExposed();
    $result = $sender->isValidNumber($number);
    $this->assertEquals(true, $result);
	}
}

class SmsSenderWithIsValidNumberExposed extends A25_SmsSender
{
  public function isValidNumber($to_number) {
    return parent::isValidNumber($to_number);
  }
}
