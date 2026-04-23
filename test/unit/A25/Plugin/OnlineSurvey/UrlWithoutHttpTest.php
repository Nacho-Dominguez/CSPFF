<?php

require_once dirname(__FILE__) . '/../../../../../plugins/OnlineSurvey.php';

class test_unit_A25_Plugin_OnlineSurvey_UrlWithoutHttpTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function replacesUrl()
	{
    $string = 'http://aliveat25.us/co/';
    $survey = new OnlineSurveyWithUrlWithoutHttpExposed();
    $result = $survey->urlWithoutHttp($string);
    $this->assertEquals('aliveat25.us/co/', $result);
	}
}

class OnlineSurveyWithUrlWithoutHttpExposed extends A25_Plugin_OnlineSurvey
{
  public function urlWithoutHttp($url) {
    return parent::urlWithoutHttp($url);
  }
}
