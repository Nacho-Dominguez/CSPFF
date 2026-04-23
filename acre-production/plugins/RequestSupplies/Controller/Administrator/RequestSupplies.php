<?php

// This plugin is still in development and has not been activated.
class Controller_Administrator_RequestSupplies extends Controller
{ 
  public function executeTask()
  {
    echo '<h1>Instructor Supplies Request</h1>';
    echo 'Red fields are required';
    $request = new A25_Form_RequestSupplies('mosmsg=Successfully+Sent+Supply+Request');
    $request->run($_POST);
  }
}