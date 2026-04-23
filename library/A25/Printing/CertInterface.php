<?php

namespace Acre\A25\Printing;

interface CertInterface
{
    public function generate(\A25_Record_Enroll $enroll);
    public function writeToLicenseNumberLines($text);
    public function bigTextTop($text);
    public function bigTextMiddle($text);
    public function bigTextBottom($text);
}
