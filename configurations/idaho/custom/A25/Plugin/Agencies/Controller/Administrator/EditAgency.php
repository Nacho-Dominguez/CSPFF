<?php

class Controller_Administrator_EditAgency extends Controller
{ 
  public function executeTask()
  {
    if (!A25_DI::User()->isAdminOrHigher()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }
		A25_FormLoader::run('Agency', null);
  }
}