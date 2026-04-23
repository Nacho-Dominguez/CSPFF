<?php

class A25_Plugin_MarijuanaPolicy implements A25_ListenerI_AppendCourseComments
{
  public function afterCourseComments()
  {
    return '<p style="text-align: left;">Please note that use or possession of '
        . '<a href="' . A25_DI::PlatformConfig()->marijuanaPolicyLink() . '" title="Marijuana Policy">'
        . 'marijuana is strictly prohibited</a> at the class.</p>';
  }
}
