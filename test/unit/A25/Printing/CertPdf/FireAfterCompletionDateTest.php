<?php

namespace Acre\A25\Printing;

class FireAfterCompletionDateTest extends \test_Framework_UnitTestCase
{
    /**
     * @test
     */
    public function callsListenerOfNscCert()
    {
        $enroll = \Acre\TestHelpers\InMemoryFixtures::enrollment();
        $listener = $this->mock('Acre\A25\Printing\CertPdfListener');

        $certPdf = new ExposedNscCert(new PdfGenerator(), array($listener), new \A25_CertPdfSettings_New());

        $listener->expects($this->once())->method('afterCompletionDate');

        $certPdf->fireAfterCompletionDate($enroll);
    }
    /**
     * @test
     */
    public function skipsNonListenerOfNscCert()
    {
        $enroll = \Acre\TestHelpers\InMemoryFixtures::enrollment();
        $listener = $this->mock('Acre\A25\Printing\NscCertNonListener');

        $certPdf = new ExposedNscCert(new PdfGenerator(), array($listener), new \A25_CertPdfSettings_New());

        $listener->expects($this->never())->method('afterCompletionDate');

        $certPdf->fireAfterCompletionDate($enroll);
    }
}

class ExposedNscCert extends NscCert
{
    public function fireAfterCompletionDate(\A25_Record_Enroll $enroll)
    {
        return parent::fireAfterCompletionDate($enroll);
    }
}

class CertPdfListener implements \A25_ListenerI_CertPdf
{
    public function afterCompletionDate(
        CertInterface $certPdf,
        \A25_Record_Enroll $enroll
    ) {
    }
}

class NscCertNonListener
{
    public function afterCompletionDate(CertInterface $certPdf)
    {
    }
}
