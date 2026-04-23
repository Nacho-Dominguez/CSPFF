<?php

namespace Acre\TestHelpers;

use A25_Record_Course as Course;
use A25_Record_Location as Location;
use A25_Record_LocationParent as LocationParent;

class InMemoryFixtures
{
    /**
     * Creates a simple public course.  It's good for tests that just need a
     * standard course, without caring about its details.
     *
     * @return A25_Record_Course
     */
    public static function courseIn3Days()
    {
        $course = new Course();
        $course->course_type_id = Course::typeId_Public;
        $course->status_id = Course::statusId_Open;
        $course->course_capacity = 20;
        $course->Location = self::location();
        $course->setCourseTime(strtotime('+3 days 8am'));
        $course->Instructor = self::Instructor();

        return $course;
    }

    /**
     * @param string $lastname
     * @return A25_Record_Enroll
     */
    public static function enrollment($lastname = 'Smith')
    {
        $hear_about_id = 2;
        $student = self::Student($lastname);
        $course = self::CourseIn3Days();
        $enrollment = $student->enrollInCourse(
            $course,
            $hear_about_id,
            \A25_Record_ReasonType::reasonTypeId_ParentsRequired
        );

        return $enrollment;
    }

    /**
     * @param string $lastname
     * @return A25_Record_User
     */
    public static function instructor($lastname = 'Johnson')
    {
        $instructor = self::User($lastname);
        $instructor->usertype = 'Instructor';
        $instructor->gid = 26;
        $instructor->single_fee = 300;
        $instructor->multiple_fee = 200;

        return $instructor;
    }

    /**
     * @return A25_Record_Location
     */
    public static function location($locationParentRecord = null)
    {
        $locationRecord = new Location();
        $locationRecord->location_name = "Somewhere";
        $locationRecord->state='CO';
        $locationRecord->zip = '80401';
        if ($locationParentRecord == null) {
            $locationParentRecord = self::locationParent();
        }
        $locationRecord->assignParent($locationParentRecord);

        return $locationRecord;
    }

    /**
     * @return A25_Record_LocationParent
     */
    public static function locationParent()
    {
        $locationParentRecord = new LocationParent();
        $locationParentRecord->location_name = "HereAndThere";
        $locationParentRecord->fee = 30;
        $locationParentRecord->late_fee = 10;
        $locationParentRecord->late_fee_deadline = 48;
        $locationParentRecord->state = 'CO';

        return $locationParentRecord;
    }

    /**
     * Returns a filled in student record, with last name of $lastName.
     *
     * @param string $lastName
     * @return A25_Record_Student
     */
    public static function student($lastName = 'Smith')
    {
        $student = new \A25_Record_Student();
        $student->first_name = 'John';
        $student->last_name = $lastName;
        $student->address_1 = '123 Brown Dr';
        $student->city = 'Denver';
        $student->zip = '80202';
        $student->state = 'CO';
        $student->home_phone = '202-123-4567';
        $student->userid = 'jelway';
        $student->gender = 'M';
        $student->date_of_birth = date('Y-m-d', strtotime('-' . (\PlatformConfig::minAge+1) . ' years'));
        $student->created = '1999-11-30';
        $student->license_status = \A25_Record_LicenseStatus::statusId_unlicensed;
        $student->password = '80202';

        return $student;
    }

    public static function user($name = 'Johnson')
    {
        $user = new \A25_Record_User();
        $user->name = $name;
        $user->username = 'test'.$user->name;
        $user->password = 'password';
        $user->usertype = 'Administrator';
        $user->email = 'fake@fake.com';
        $user->state = 'CO';
        $user->gid = 24;

        return $user;
    }
}
