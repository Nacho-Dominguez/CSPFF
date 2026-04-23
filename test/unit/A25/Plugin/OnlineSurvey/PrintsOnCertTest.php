<?php

use Acre\A25\Printing\NscCert;
use Acre\TestFramework\DumbCallEcho;
use Acre\TestHelpers\InMemoryFixtures;

class test_unit_A25_Plugin_OnlineSurvey_PrintsOnCertTest extends
    \test_Framework_UnitTestCase
{
    /**
     * @test
     */
    public function prints()
    {
        require_once dirname(__FILE__) . '/../../../../../plugins/OnlineSurvey.php';

        $tracker = new DumbCallEcho();
        $printer = new NscCert($tracker, array(new A25_Plugin_OnlineSurvey()), new A25_CertPdfSettings_New());
        $enroll = InMemoryFixtures::enrollment();
        $enroll->Course->course_start_date = '2014-07-20 09:00:00';
        $printer->generate($enroll);

        $surveyLink = substr(ServerConfig::staticHttpUrl(), 7) . 'survey?id=';

        $this->assertEquals(
            'Method: addPage. Args: array(0) {
}

Method: setFont. Args: array(3) {
  [0]=>
  string(5) "Arial"
  [1]=>
  string(0) ""
  [2]=>
  int(9)
}

Method: setXY. Args: array(2) {
  [0]=>
  int(-33)
  [1]=>
  int(39)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  NULL
}

Method: setXY. Args: array(2) {
  [0]=>
  int(60)
  [1]=>
  int(45)
}

Method: writeOnSameLine. Args: array(1) {
  [0]=>
  string(13) "' . $enroll->Course->formattedDate('course_start_date', 'F j, Y') . '"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(25)
  [1]=>
  int(54)
}

Method: displayLeft. Args: array(1) {
  [0]=>
  string(10) "John Smith"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(25)
  [1]=>
  int(59)
}

Method: displayLeft. Args: array(1) {
  [0]=>
  string(12) "123 Brown Dr"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(25)
  [1]=>
  int(64)
}

Method: displayLeft. Args: array(1) {
  [0]=>
  string(0) ""
}

Method: setXY. Args: array(2) {
  [0]=>
  int(25)
  [1]=>
  int(69)
}

Method: displayLeft. Args: array(1) {
  [0]=>
  string(16) "Denver, CO 80202"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(60)
  [1]=>
  float(79.5)
}

Method: displayLeft. Args: array(1) {
  [0]=>
  string(39) "Colorado State Patrol Family Foundation"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(60)
  [1]=>
  float(84.5)
}

Method: displayLeft. Args: array(1) {
  [0]=>
  string(7) "Johnson"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(60)
  [1]=>
  float(89.5)
}

Method: displayLeft. Args: array(1) {
  [0]=>
  NULL
}

Method: setXY. Args: array(2) {
  [0]=>
  int(-33)
  [1]=>
  float(-82.5)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  NULL
}

Method: setXY. Args: array(2) {
  [0]=>
  int(-45)
  [1]=>
  float(-78.5)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  string(10) "John Smith"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(-23)
  [1]=>
  float(-55.5)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  string(13) "' . $enroll->Course->formattedDate('course_start_date', 'F j, Y') . '"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(-45)
  [1]=>
  float(-33.5)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  string(39) "Colorado State Patrol Family Foundation"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(-69)
  [1]=>
  float(-23.5)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  string(7) "Johnson"
}

Method: setXY. Args: array(2) {
  [0]=>
  int(-23)
  [1]=>
  float(-23.5)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  NULL
}

Method: setXY. Args: array(2) {
  [0]=>
  int(107)
  [1]=>
  float(160.5)
}

Method: setFont. Args: array(3) {
  [0]=>
  string(0) ""
  [1]=>
  string(2) "BU"
  [2]=>
  int(11)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  string(59) "**WE VALUE YOUR INPUT. PLEASE TAKE OUR SURVEY BY GOING TO**"
}

Method: moveDown. Args: array(1) {
  [0]=>
  int(5)
}

Method: displayCentered. Args: array(1) {
  [0]=>
  string(' . strlen($surveyLink) . ') "' . $surveyLink . '"
}

Method: moveDown. Args: array(1) {
  [0]=>
  int(5)
}

Method: setFont. Args: array(3) {
  [0]=>
  string(5) "Arial"
  [1]=>
  string(0) ""
  [2]=>
  int(9)
}

Method: output. Args: array(0) {
}

',
            $tracker->callLog
        );


    }
}
