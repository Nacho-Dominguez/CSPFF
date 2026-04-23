<?php

/**
 * This object actually sends emails.  It is different from the
 * A25_Emailer class, which handles saving Student Messages to the
 * database, and then emails them.  Someday, we should rename one or both of
 * the classes.
 */
class A25_Mailer
{
    public function mail($address, $subject, $body, $isHtml = 1, $alt_body = null, $attachment = null)
    {
        $fromAddress = A25_DI::PlatformConfig()->sendFromEmail;
        $fromName = PlatformConfig::agency;
        // Validate email address syntax
        if (!preg_match('/.+@.+\..+/', $address)) {
            return false;
        }

        require_once dirname(__FILE__) . '/../../includes/joomlaClasses.php';

        if($alt_body == null && $isHtml == 1) {
            $converter = new A25_HtmlTextConverter();
            $alt_body = $converter->wrapText($converter->stripHtml($body));
        }
        
        /**
         * $mail is an object of type PHPMailer
         */
        $mail = mosCreateMail($fromAddress, $fromName, $subject, $body);
        $mail->AddReplyTo(A25_DI::PlatformConfig()->contactEmailAddress);
        $mail->AddAddress($address);
        $mail->AddBCC('aliveat25copies@gmail.com');
        $mail->IsHTML($isHtml);
        $mail->AltBody = $alt_body;
        $mail->AddAttachment($attachment);
        $mail->Send();
    }
}
