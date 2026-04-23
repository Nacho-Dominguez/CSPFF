<?php
namespace Acre\A25\Printing;

class NscCert extends Cert
{
    private $settings;

    public function __construct($generator, $listeners, \A25_CertPdfSettings $settings)
    {
        parent::__construct($generator, $listeners);
        $this->settings = $settings;
    }

    public function printText(\A25_Record_Enroll $enroll)
    {
        $this->generator->addPage();
        $student = $enroll->Student;
        $course = $enroll->Course;

        $studentFullName = (ucwords(strtolower($student->first_name))) . ' '
                . ucwords(strtolower($student->last_name));

        $this->fontStyleNormal();

        $instructor = $enroll->Course->Instructor;

        $columnControlNum = -33;
        $this->generator->setXY($columnControlNum, 39);
        $this->generator->displayCentered($instructor->nsc);

        $column1 = 60;

        $completionDate = $course->formattedDate('course_start_date', 'F j, Y');
        $this->generator->setXY($column1, $this->settings->rowForTopCompletionDate());
        $this->generator->writeOnSameLine($completionDate);

        $this->generator->setXY(
            $this->settings->columnForStudentInfo(),
            $this->settings->rowForTopStudentName()
        );
        $this->generator->displayLeft($studentFullName);
        $this->generator->setXY(
            $this->settings->columnForStudentInfo(),
            $this->settings->rowForTopStudentName() + 5
        );
        $this->generator->displayLeft(ucwords(strtolower($student->address_1)));
        $this->generator->setXY(
            $this->settings->columnForStudentInfo(),
            $this->settings->rowForTopStudentName() + 10
        );
        $this->generator->displayLeft(ucwords(strtolower($student->address_2)));
        $this->generator->setXY(
            $this->settings->columnForStudentInfo(),
            $this->settings->rowForTopStudentName() + 15
        );
        $this->generator->displayLeft(ucwords(strtolower($student->city)) . ', '
                . $student->state . ' ' . $student->zip);


        $this->generator->setXY($column1, 79.5);
        $this->generator->displayLeft(\PlatformConfig::shortAgency);
        $this->generator->setXY($column1, 84.5);
        $this->generator->displayLeft($instructor->name);
        $this->generator->setXY($column1, 89.5);
        $this->generator->displayLeft($instructor->control);

        $this->generator->setXY(
            $columnControlNum,
            $this->settings->rowForBottomControlNumber()
        );
        $this->generator->displayCentered($instructor->nsc);

        $columnLine = -45;
        $this->generator->setXY(
            $columnLine,
            $this->settings->rowForBottomStudentName()
        );
        $this->generator->displayCentered($studentFullName);

        $columnBottomLeft = -69;
        $columnBottomRight = -23;

        $this->generator->setXY(
            $columnBottomRight,
            $this->settings->rowForBottomCompletionDate()
        );
        $this->generator->displayCentered($completionDate);

        $this->generator->setXY($columnLine, $this->settings->rowForBottomAgency());
        $this->generator->displayCentered(\PlatformConfig::shortAgency);

        $row2 = $this->settings->rowForBottomCertPrintingLine();
        $this->generator->setXY($columnBottomLeft, $row2);
        $this->generator->displayCentered($instructor->name);
        $this->generator->setXY($columnBottomRight, $row2);
        $this->generator->displayCentered($instructor->control);
    }

    public function writeToLicenseNumberLines($text)
    {
        $this->generator->setXY(60, 40);
        $this->generator->displayLeft($text);

        $this->generator->setXY(-69, -52.5);
        $this->generator->displayCentered($text);
    }
    public function bigTextTop($text)
    {
        $this->generator->setXY(107, 9);
        $this->bigText($text);
    }
    public function bigTextMiddle($text)
    {
        $this->generator->setXY(107, \PlatformConfig::rowForBigTextMiddleOnCertificate);
        $this->bigText($text);
    }
    public function bigTextBottom($text)
    {
        $this->generator->setXY(107, 160.5);
        $this->bigText($text);
    }
    private function bigText($text)
    {
        $this->fontStyleEmphasize();
        $array = $this->splitTextOnLineBreak($text);
        foreach ($array as $line) {
            $this->generator->displayCentered($line);
            $this->generator->moveDown(5);
        }
        $this->fontStyleNormal();
    }
    private function splitTextOnLineBreak($string)
    {
        return preg_split('/\\\\[N|n]/', $string);
    }
    private function fontStyleEmphasize()
    {
        $this->generator->setFont('', 'BU', 11);
    }
    private function fontStyleNormal()
    {
        $this->generator->setFont('Arial', '', 9);
    }
}
