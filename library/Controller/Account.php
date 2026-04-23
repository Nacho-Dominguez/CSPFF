<?php

class Controller_Account extends Controller
{
  public function executeTask()
  {
    $student = A25_CookieMonster::getStudentFromCookie();

    $page = A25_DI::Factory()->Account($student);
    $page->kickOutIfNecessary();
		A25_DoctrineRecord::$disableSave = true;
    $page->render();
  }
}
