<?php

class Controller_SafetyserveResponse extends Controller
{
  public function executeTask()
  {
    $body_generator = new A25_ErrorEmailBody();
    $message = $body_generator->getDump() . "\n\n"
        . $body_generator->postDump();

    A25_DI::Mailer()->mail('jonathan@appdevl.net',
        'Safetyserve Response', $message, false);
  }
}
