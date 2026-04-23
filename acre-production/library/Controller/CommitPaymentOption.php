<?php

require_once(ServerConfig::webRoot
        . '/administrator/components/com_student/student.auth.php');
class Controller_CommitPaymentOption extends Controller
{
    private $student;
    private $course;

    public function executeTask()
    {
        $this->student = A25_CookieMonster::getStudentFromCookie();
        $course_id = (int)$_REQUEST['course_id'];

        A25_Functions::checkCourse($course_id);
        $this->commitPaymentOption($course_id);
    }

    private function commitPaymentOption($course_id)
    {
        $this->course = A25_Record_Course::retrieve($course_id);

        $strategy = new Acre\A25\SeatExpiration\SeatExpirationManager(
            A25_DI::PlatformConfig()->kickOutInterfaces()
        );

        $courseRunner = new A25_CourseRunner($this->student, $this->course);
        $enroll = $courseRunner->createEnrollment();
        $strategy->setKickOutDate($enroll);
        $courseRunner->commitEnrollment();
        $this->redirect();
    }

    private function redirect()
    {
        A25_DI::Redirector()->redirectBasedOnSiteRoot('/account', $this->message());
    }

    private function message()
    {
        if ($this->course->course_type_id == A25_Record_Course::typeId_Spanish) {
            $msg = 'Inscripci&oacute;n al curso completada';
        }
        else {
            $msg = 'Course Enrollment Completed';
        }
        if ($this->student->getAccountBalance() > 0) {
            if ($this->course->course_type_id == A25_Record_Course::typeId_Spanish) {
                $msg .= ' - Pago Requerido';
            }
            else {
                $msg .= ' - Payment Required';
            }
        }
        return $msg;
    }
}
