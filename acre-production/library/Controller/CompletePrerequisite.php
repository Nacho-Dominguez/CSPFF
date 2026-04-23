<?php

require_once(ServerConfig::webRoot
        . '/administrator/components/com_student/student.auth.php');
class Controller_CompletePrerequisite extends Controller
{
    public function executeTask()
    {
        $xref_id = (int)$_REQUEST['xref_id'];

        $this->incrementPrerequisiteCounter($xref_id);
    }

    private function incrementPrerequisiteCounter($xref_id)
    {
        $enroll = A25_Record_Enroll::retrieve($xref_id);
        $enroll->prerequisites_completed = $enroll->prerequisites_completed + 1;
        $enroll->save();
        $this->redirect();
    }

    private function redirect()
    {
        A25_DI::Redirector()->redirectBasedOnSiteRoot('/account');
    }
}
