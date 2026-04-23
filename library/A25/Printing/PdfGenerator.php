<?php

namespace Acre\A25\Printing;

require_once dirname(__FILE__) . '/../../../third-party/fpdf/fpdf.php';

class PdfGenerator
{
    /**
     * @var FPDF
     */
    private $pdf;

    public function __construct()
    {
        $this->pdf = new \FPDF('P', 'mm', 'Letter');
        $this->pdf->SetAutoPageBreak(false);
    }
    public function addPage($orientation = null)
    {
        $this->pdf->AddPage($orientation);
    }
    public function displayLeft($string)
    {
        $this->pdf->Cell(2, 3.15, $string);
    }

    public function displayCentered($string)
    {
        $this->pdf->Cell(.1, 3.15, $string, 0, 0, 'C');
    }
    public function moveDown($y_diff)
    {
        $this->pdf->SetXY($this->pdf->GetX(), $this->pdf->GetY() + $y_diff);
    }
    public function moveRight($x_diff)
    {
        $this->pdf->SetXY($this->pdf->GetX() + $x_diff, $this->pdf->GetY());
    }
    public function writeOnSameLine($string)
    {
        $this->pdf->Write(2, $string);
    }
    public function setFont($family = '', $style = '', $size = null)
    {
        $this->pdf->SetFont($family, $style, $size);
    }
    public function setXY($x, $y)
    {
        $this->pdf->SetXY($x, $y);
    }
    public function output()
    {
        $this->pdf->Output();
    }
    public function insertImage($url, $width)
    {
        $this->pdf->Image($url, null, null, $width);
    }
}
