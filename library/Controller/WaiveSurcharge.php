<?php

class Controller_WaiveSurcharge extends Controller
{
  public function executeTask()
  {
    $view = new A25_View_WaiveSurchargeForm();
    echo $view->run();
  }
}
