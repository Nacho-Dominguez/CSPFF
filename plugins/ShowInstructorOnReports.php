<?php

class A25_Plugin_ShowInstructorOnReports implements
    A25_ListenerI_AppendEnrollmentReportFormatRow,
    A25_ListenerI_AppendCourseReportFormatRow
{
    public function appendEnrollmentReportFormatRow(
        array $formatRow,
        A25_Record_Enroll $enroll
    ) {
        if ($enroll->Course->relatedIsDefined('Instructor')) {
            $formatRow['Instructor 1'] = $enroll->Course->Instructor->name;
            $formatRow['Instructor 1 number'] = $enroll->Course->Instructor->control;
        }
        if ($enroll->Course->relatedIsDefined('Instructor2')) {
            $formatRow['Instructor 2'] = $enroll->Course->Instructor2->name;
            $formatRow['Instructor 2 number'] = $enroll->Course->Instructor2->control;
        }
        return $formatRow;
    }
    public function appendCourseReportFormatRow(
        array $formatRow,
        A25_Record_Course $course
    ) {
        if ($course->relatedIsDefined('Instructor')) {
            $formatRow['Instructor 1'] = $course->Instructor->name;
            $formatRow['Instructor 1 number'] = $course->Instructor->control;
        }
        if ($course->relatedIsDefined('Instructor2')) {
            $formatRow['Instructor 2'] = $course->Instructor2->name;
            $formatRow['Instructor 2 number'] = $course->Instructor2->control;
        }
        return $formatRow;
    }
}
