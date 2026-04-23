<?php

class Controller_Administrator_EditFund extends Controller
{
    public function executeTask()
    {
        if (!A25_DI::User()->isAdminOrHigher()) {
            echo 'Sorry, your account is not allowed to access this page.';
            exit();
        }
        A25_FormLoader::run('Fund', null);
    }
}
