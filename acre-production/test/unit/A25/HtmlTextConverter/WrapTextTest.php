<?php

class test_unit_A25_HtmlTextConverter_WrapTextTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
  public function wrapsLineCorrectly()
  {
    $text = 'This is a line which, assuming it has enough characters, will be long enough that it gets wrapped';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->wrapText($text);
    $expected = 'This is a line which, assuming it has enough characters, will be long enough
that it gets wrapped';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function shortLineIsNotWrapped()
  {
    $text = 'This is a short line which should not be wrapped because it is not long enough';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->wrapText($text);
    $expected = 'This is a short line which should not be wrapped because it is not long enough';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function blankLinesAreNotRemoved()
  {
    $text = 'This is some text

with blank lines in between, and other lines that have more than enough characters so that they



get wrapped too';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->wrapText($text);
    $expected = 'This is some text

with blank lines in between, and other lines that have more than enough
characters so that they



get wrapped too';
    $this->assertEquals($expected, $text);
  }
}
