<?php

use Acre\A25\Printing\CertInterface;

interface A25_ListenerI_CertPdf
{
    public function afterCompletionDate(
        CertInterface $certPdf,
        A25_Record_Enroll $enroll
    );
}
