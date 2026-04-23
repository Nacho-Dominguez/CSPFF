<?php

class Controller_PostDump extends Controller
{
  public function executeTask()
  {
    $postdump = "POST:<br />";
    foreach($_POST as $key => $value)
    {
      $postdump .= "'$key' => '$value'<br />";
    }
    echo $postdump;
  }
}
