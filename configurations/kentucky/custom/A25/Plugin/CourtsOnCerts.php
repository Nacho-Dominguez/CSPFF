<?php

use Acre\A25\Printing\CertInterface;

class A25_Plugin_CourtsOnCerts implements A25_ListenerI_CertPdf
{
    public function afterCompletionDate(
        CertInterface $certPdf,
        A25_Record_Enroll $enroll
    ) {
        if ($this->shouldNotPrint($enroll)) {
            return;
        }

        $topText = $this->setCourtTextTop($enroll);
        $certPdf->bigTextTop($topText);

        $middleText = $this->setCourtTextMiddle($enroll);
        $certPdf->bigTextMiddle($middleText);
    }

    private function setCourtTextTop(A25_Record_Enroll $enroll)
    {
        if ($enroll->isLegalMatter()) {
            return A25_DI::PlatformConfig()->courtTextForCertsTop($enroll);
        } else {
            return strtoupper('**Not valid for court/legal proceedings**');
        }
    }

    private function setCourtTextMiddle(A25_Record_Enroll $enroll)
    {
        if ($enroll->isLegalMatter()) {
            return A25_DI::PlatformConfig()->courtTextForCertsMiddle($enroll);
        } else {
            return strtoupper('**Not valid for court/legal proceedings**');
        }
    }

    /**
     * PlatformConfig::hideCourtInfoOnNonPublicCerts can be set so that court
     * info is only printed on certificates for public classes.
     *
     * @param A25_Record_Enroll $enroll
     * @return boolean
     */
    private function shouldNotPrint(A25_Record_Enroll $enroll)
    {
        if (PlatformConfig::hideCourtInfoOnNonPublicCerts &&
                $enroll->Course->course_type_id !=
                        A25_Record_Course::typeId_Public) {
            return true;
        }

        return false;
    }
}
