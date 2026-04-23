<?php
namespace Acre\A25\SeatExpiration;

class DaysBeforeCourseKickOutTest extends \test_Framework_UnitTestCase
{
    private $course;
    private $kickOut;
    private function setUpTests($startDate)
    {
        $config = new \PlatformConfig();
        $config->kickOutBeforeCourseDeadline = '15 days';
        \A25_DI::setPlatformConfig($config);

        $this->course = new \A25_Record_Course();
        $this->course->course_start_date = $startDate;

        $this->kickOut = new DaysBeforeCourseKickOut();
    }
    /**
     * @test
     */
    public function setsTo15DaysBeforeCourse()
    {
        $this->setUpTests('2015-07-01');
        $date = $this->kickOut->kickOutDate($this->course);
        $this->assertEquals('2015-06-16 16:00:00', $date);
    }
    /**
     * @test
     */
    public function accountsForWeekend()
    {
        $this->setUpTests('2015-06-28');
        $date = $this->kickOut->kickOutDate($this->course);
        $this->assertEquals('2015-06-15 16:00:00', $date);
    }
}
