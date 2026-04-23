<?php

require_once(dirname(__FILE__) . '/../../../third-party/fpdf/fpdf.php');
require_once(dirname(__FILE__) . '/../../../third-party/FPDI-1.4.2/fpdi.php');

class A25_SurveyPrinter extends A25_StrictObject
{
    private $pdf;

    public function __construct()
    {
    }

    public function generate(A25_Record_Course $course)
    {
        $this->pdf = new FPDI('P', 'mm', 'letter');
        $this->pdf->AddPage();
        $this->pdf->setSourceFile(A25_DI::PlatformConfig()->evaluationTemplate);
        $template = $this->pdf->importPage(1);
        $this->pdf->useTemplate($template);

        $this->pdf->SetFont('Arial', 'B', 24);
        $this->pdf->Cell(0, 8, PlatformConfig::courseTitle, 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 11);
        $this->pdf->SetXY(39, 29.5);
        $this->pdf->Write(0, PlatformConfig::courseTitle);
        $this->pdf->SetXY(107, 29.5);
        $this->pdf->Write(0, $course->date);
        $this->pdf->SetXY(153, 25.5);
        $this->pdf->MultiCell(0, 8, $course->getLocationName(), 0, 'L');
        $this->pdf->SetXY(50, 37.5);
        $this->pdf->Write(0, $course->instructorName());
        if ($course->instructor_2_id > 0) {
            $this->pdf->Write(0, ', ' . $course->instructor2Name());
        }

        $this->pdf->Output();
    }
}
