<?php

class test_unit_A25_HtmlTextConverter_StripHtmlTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
  public function stripsLinkTags()
  {
    $text = '<a href="http://www.whatever.com/path/" onclick="alert(\'hello\')">Here is some text</a>';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = 'Here is some text';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function stripsMultipleWhitespace()
  {
    $text = '<i>
      Here is    some text</i>';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = 'Here is some text';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function divTagsHaveLineBreakBeforeAndAfter()
  {
    $text = '<div>Div 1</div><DIV>Div 2</DIV><div style="who cares"
        >Div 3</div>';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = '
Div 1
Div 2
Div 3
';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function paragraphTagsHaveBlankLineBeforeAndAfter()
  {
    $text = '<p>Paragraph 1</p><P>Paragraph 2</P><p style="who cares"
        >Paragraph 3</p>';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = '
Paragraph 1

Paragraph 2

Paragraph 3
';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function headingTagsHaveTwoBlankLinesBeforeAndOneAfter()
  {
    $text = '<h1 align="center">First line</h1><h3>Second line</h3>Third line';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = '

First line


Second line
Third line';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function lineBreakTagsHaveBlankLine()
  {
    $text = 'First line<br><br/>Second line<BR /><br
        />Third line';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = 'First line

Second line

Third line';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function whitespaceRemainsOutsideTag()
  {
    $text = 'h <i>j';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = 'h j';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function whitespaceRemainsBetweenTags()
  {
    $text = 'h</i> <i>j';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = 'h j';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function dashesAreReplaced()
  {
    $text = 'blahblah&ndash;blah';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = 'blahblah-blah';
    $this->assertEquals($expected, $text);
  }
	/**
	 * @test
	 */
  public function atMostTwoBlankLines()
  {
    $text = 'Hello<p></p><p></p><p></p>Goodbye';
    $converter = new A25_HtmlTextConverter();
    $text = $converter->stripHtml($text);
    $expected = 'Hello


Goodbye';
    $this->assertEquals($expected, $text);
  }
}
