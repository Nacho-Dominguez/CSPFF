<?php

require_once(dirname(__FILE__) . '/../../../third-party/fpdf/fpdf.php');
require_once(dirname(__FILE__) . '/../../../third-party/FPDI-1.4.2/fpdi.php');

class A25_Printing_SPABCert extends A25_StrictObject
{
    private $pdf;

    public function __construct()
    {
    }

    public function generate(A25_Record_Enroll $enroll)
    {
        $student = $enroll->Student;
        $studentFullName = (ucwords(strtolower($student->first_name))) . ' '
                . ucwords(strtolower($student->last_name));
        $course = $enroll->Course;
        $completionDate = $course->formattedDate('course_start_date', 'F j, Y');
        
        $isOriginal = $this->isOriginalTraining($student);
        if ($isOriginal) {
            $originalText = 'the 12 units of Original';
        }
        else {
            $originalText = '10 hours of Renewal';
        }
        
        $this->pdf = new FPDI('L', 'mm', 'letter');
        $this->pdf->AddPage();
        $this->pdf->setSourceFile(dirname(__FILE__) . '/SPABCert.pdf');
        $template = $this->pdf->importPage(1);
        $this->pdf->useTemplate($template);

        $this->pdf->SetFont('Arial', '', 24);
        $this->pdf->setXY(139.7, 70);
        $this->displayCentered($studentFullName);
        $this->moveDown(20);
        $this->pdf->SetFont('Arial', '', 16);
        $this->displayCentered('has completed ' . $originalText . ' SPAB training on');
        $this->moveDown(20);
        $this->pdf->SetFont('Arial', '', 24);
        $this->displayCentered($completionDate);
        $this->moveDown(20);
        $this->pdf->SetFont('Arial', '', 16);
        $this->displayCentered('as required by the California Department of Education');

        $this->pdf->Output();
    }
    
    private function isOriginalTraining(A25_Record_Student $student) {
        $enrollments = $student->Enrollments;
        foreach($enrollments as $e) {
            $course = $e->Course;
            if ($e->isComplete() && $course->course_type_id == 1) {
                return true;
            }
        }
        return false;
    }
    
    private function displayCentered($string)
    {
        $this->pdf->Cell(.1, 3.15, $string, 0, 0, 'C');
    }
    private function moveDown($y_diff)
    {
        $this->pdf->SetXY($this->pdf->GetX(), $this->pdf->GetY() + $y_diff);
    }
}
