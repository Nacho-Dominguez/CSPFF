<?php

use Acre\A25\Printing\CertInterface;

class A25_Plugin_OnlineSurvey implements
    A25_ListenerI_CertPdf,
    A25_ListenerI_SendAdditionalEmail,
    A25_ListenerI_Doctrine
{
    public function sendAdditionalEmail($enroll_id, $address)
    {
        $email = new A25_Envelope(new A25_EmailContent_Survey($enroll_id));
        $email->send($address);
    }

    public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
    {
        if (!$doctrineRecord instanceof A25_Record_Enroll) {
            return;
        }

        $doctrineRecord->hasColumn('was_survey_visited', 'integer', 1, array(
         'type' => 'integer',
         'length' => 1,
         'fixed' => false,
         'unsigned' => false,
         'primary' => false,
         'default' => '0',
         'notnull' => true,
         'autoincrement' => false,
         ));
    }

    public function afterCompletionDate(
        CertInterface $certPdf,
        A25_Record_Enroll $enroll
    ) {
        $text = strtoupper('**We value your input. Please take our survey by going to**\n')
            . $this->urlWithoutHttp(ServerConfig::staticHttpUrl())
            . 'survey?id=' . $enroll->xref_id;
        $certPdf->bigTextBottom($text);
    }

    protected function urlWithoutHttp($url)
    {
        return substr($url, 7);
    }
}

set_include_path(
    ServerConfig::webRoot . '/plugins/OnlineSurvey' . PATH_SEPARATOR
    . get_include_path()
);
