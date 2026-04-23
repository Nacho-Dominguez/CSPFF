<?php

class A25_Remind_AutomatedReport extends A25_Remind
{
    public function whom()
    {
        return A25_DI::PlatformConfig()->automatedReportRecipients;
    }

    protected function sendToIndividual($recipient)
    {
        $subject = A25_DI::PlatformConfig()->automatedReportTitle . ' for ' . A25_Functions::stringToDate('Yesterday');
        $attachment = dirname(__FILE__) . '/report.csv';
        $body = self::emailBody($attachment);
        A25_DI::Mailer()->mail($recipient, $subject, $body, 0, null, $attachment);
    }

    /**
     * @return string
     */
    private static function emailBody($attachment)
    {
        $query = A25_DI::PlatformConfig()->automatedReportQuery();
        $records = $query->execute();
        $return = '';
        foreach ($records as $record) {
            $return .= A25_DI::PlatformConfig()->automatedReportFields($record);
        }
        if ($return == '') {
            $return = 'No records today';
        }
        file_put_contents($attachment, $return);
        return A25_DI::PlatformConfig()->automatedReportTitle . ' for ' . A25_Functions::stringToDate('Yesterday');
    }
}
