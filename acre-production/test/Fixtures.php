<?php

use Acre\TestHelpers\InMemoryFixtures;

/**
 * @todo-jon-small-low - try removing these 2 lines and make sure that
 * integration tests still pass
 */
define('_VALID_MOS', 1);
require_once(dirname(__FILE__) . '/../includes/database.php');

class test_Fixtures
{
    /**
     * @todo-jon-low-small - rename to CourseIn3Days()
     *
     * Creates a simple public course.  It's good for tests that just need a
     * standard course, without caring about its details.
     *
     * @return A25_Record_Course
     */
    public static function CourseTomorrow()
    {
        $course = InMemoryFixtures::courseIn3Days();
        $course->save();

        return $course;
    }

    /**
     * Creates a course that happened 2 days ago.
     *
     * @return A25_Record_Course
     */
    public static function PastCourse()
    {
        $courseRecord = test_Fixtures::CourseTomorrow();
        $courseRecord->setCourseTime(strtotime('-2 days'));
        $courseRecord->checkAndStore();

        return $courseRecord;
    }

    /**
     * Returns a filled in student record, with last name of $lastName.
     *
     * @param string $lastName
     * @return A25_Record_Student
     */
    public static function Student($lastName = 'Smith')
    {
        $student = InMemoryFixtures::student($lastName);
        $student->save();

        return $student;
    }

    /**
     * @param string $lastname
     * @return A25_Record_Enroll
     */
    public static function Enrollment($lastname = 'Smith')
    {
        $hear_about_id = 2;
        $student = test_Fixtures::Student($lastname);
        $course = test_Fixtures::CourseTomorrow();
        $enrollment = $student->enrollInCourse(
            $course,
            $hear_about_id,
            A25_Record_ReasonType::reasonTypeId_ParentsRequired
        );
        $enrollment->save();

        return $enrollment;
    }

    /**
     * @param string $lastname
     * @return A25_Record_User
     */
    public static function Instructor($lastname = 'Johnson')
    {
        $instructor = InMemoryFixtures::instructor($lastname);
        $instructor->save();

        return $instructor;
    }

    /**
     *
     * @return A25_Record_Court
     */
    public static function StandardTestCourt()
    {
        $court = new A25_Record_Court();
        $zip = self::ZipCode();
        $court->court_name = 'Spanky Court House of Justice';
        $court->address_1 = "111 Fake St.";
        $court->city = "Golden";
        $court->state = "CO";
        $court->Zip = $zip;
        $court->phone = "999-999-9999";
        $court->fee = 73;
        $court->parent = 1;
        $court->save();

        return $court;
    }

    public static function ZipCode()
    {
        $zip = new A25_Record_Zip();
        $zip->zip_code = '80401';
        $zip->city = 'Golden';
        $zip->state = 'CO';
        $zip->county = 'Jefferson';
        $zip->zip_class = 'Standard';
        $zip->save();

        return $zip;
    }

    /**
     *
     * @return A25_Record_Coupon
     */
    public static function Coupon()
    {
        $coupon = new A25_Record_Coupon();
        $coupon->code = "DISCOUNT";
        $coupon->numberLeft = "1";
        $coupon->discount = "10";
        $coupon->checkAndStore();

        return $coupon;
    }

    /**
     * @return A25_Record_LocationParent
     */
    public static function LocationParent()
    {
        $locationParentRecord = InMemoryFixtures::LocationParent();
        $locationParentRecord->save();

        return $locationParentRecord;
    }
    /**
     * @return A25_Record_Location
     */
    public static function Location($locationParentRecord = null)
    {
        $locationRecord = InMemoryFixtures::Location($locationParentRecord);
        $locationRecord->save();

        return $locationRecord;
    }
    public static function User($name = 'Johnson')
    {
        $user = InMemoryFixtures::user($name);
        $user->save();

        return $user;
    }
    public static function EnrollStatus()
    {
        $status = new A25_Record_EnrollStatus();
        $status->status_id = 1;
        $status->status_name = 'Registered';
        $status->status_key = 1;
        $status->save();
    }
}
