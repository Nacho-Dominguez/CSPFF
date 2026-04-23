<?php
namespace Acre\A25\SeatExpiration;

class SetKickOutDateTest extends \test_Framework_UnitTestCase
{
    private $enroll;

    private function setUpTests($time)
    {
        $course = new \A25_Record_Course();
        $course->course_start_date = \A25_Functions::formattedDateTime($time);

        $this->enroll = new \A25_Record_Enroll();
        $this->enroll->Course = $course;
        $interfaces[] = new PostPaymentOptionDeadlineKickOut();
        $interfaces[] = new DaysAfterEnrollingKickOut();
        $strategy = new SeatExpirationManagerWithSetKickOutDateExposed($interfaces);
        $strategy->setKickOutDate($this->enroll);
    }
    /**
     * @test
     */
    public function SetsIfCourseIsNotPast()
    {
        $this->setUpTests('1 hour');
        $this->assertNotNull($this->enroll->kick_out_date);
    }

    /**
     * @test
     */
    public function DoesNotSetIfCourseIsPast()
    {
        $this->setUpTests('- 1 hour');
        $this->assertNull($this->enroll->kick_out_date);
    }

    /**
     * @test
     */
    public function setsToNullIfPlatformConfigOptionIsOffForSlowPayment()
    {
        $config = new \PlatformConfig();
        $config->kickOutBeforeDeadline = 'never';
        \A25_DI::setPlatformConfig($config);

        $this->setUpTests('1 hour');
        $this->assertNull($this->enroll->kick_out_date);
    }
}

class SeatExpirationManagerWithSetKickOutDateExposed
    extends SeatExpirationManager
{
    public function setKickOutDate(\A25_Record_Enroll $enroll)
    {
        return parent::setKickOutDate($enroll);
    }
}
