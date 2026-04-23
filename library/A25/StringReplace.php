<?php

/**
 * This class has static functions which replace certain values in strings, such
 * as !URL!, with a dynamic value.
 */
class A25_StringReplace {
	public static function secureUrl($string)
	{
		return str_replace('!URL!',ServerConfig::httpsUrlWithoutSlash(),$string);
	}
    
    /**
     * Kentucky wants separate messages in the completion email based on the
     * reason for enrollment.  If this ever extends to other states, we should
     * put the text in PlatformConfig.
     */
	public static function completionInfo($string, $reason_id)
	{
        if ($reason_id == A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit) {
            $replacement = '<b>Graduated License Program!</b></p>
<p>If you took the course to satisfy your mandated graduated driver license requirement, the KYTC Division of Driver
Licensing will have your completion in their systems, automatically, within 48 hours.
Please keep this notification as your record for completing the Graduated License Program.';
        }
        else if ($reason_id == A25_Record_ReasonType::reasonTypeId_CourtOrdered
            || $reason_id == A25_Record_ReasonType::reasonTypeId_PendingLegalMatter) {
            $replacement = '<b>Court Diversion Program!</b></p>
Your completion will be updated in our system within 24 hours.
Please keep this notification as your record for completing the Court Diversion Program.';
        }
            return str_replace('!COMPLETION!',$replacement,$string);
	}
}
?>
