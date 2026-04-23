<?php

namespace Acre\A25\Printing;

use Acre\TestFramework\DumbCallEcho;
use Acre\TestHelpers\InMemoryFixtures;

class GenerateTest extends \test_Framework_UnitTestCase
{
    public function testDefaultCertificateWithNoPlugins()
    {
        $tracker = new DumbCallEcho();
        $printer = new NscCert($tracker, \A25_ListenerManager::all(), new \A25_CertPdfSettings_New());
        $enroll = InMemoryFixtures::enrollment();
        $enroll->Course->course_start_date = '2014-07-20 09:00:00';
        $printer->generate($enroll);
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

Method: output. Args: array(0) {
}

',
            $tracker->callLog
        );
    }
}
