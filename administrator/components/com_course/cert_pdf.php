<?php

require_once(dirname(__FILE__) . '/../../../autoload.php');
A25_ErrorHandler::Initialize();

require_once(dirname(__FILE__) . '/../../../third-party/fpdf/fpdf.php');

$xref_id = (int) $_GET['id'];

$q = Doctrine_Query::create()
    ->from('A25_Record_Enroll e')
    ->leftJoin('e.Student s')
    ->leftJoin('e.Course c')
    ->leftJoin('c.Instructor i')
    ->where('e.xref_id = ?', $xref_id);

$enroll = $q->fetchOne();

$pdf = A25_DI::PlatformConfig()->certificatePrinter();
$pdf->generate($enroll);
