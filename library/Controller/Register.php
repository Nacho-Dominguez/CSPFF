<?php

class Controller_Register extends Controller
{
  public function executeTask()
  {
    echo "<h1>Create A New Account</h1>";
    echo 'Red fields are required';
    $request = new A25_Form_Record_Register();
    $request->run($_POST);
    
  }
}
