<?php

abstract class A25_EmailContent
{
  public static function wrapSubject($subject,
      $title = null)
  {
    if (!$title)
      $title = PlatformConfig::courseTitleHtml();
    return $subject . ' from ' . $title;
  }
  
  abstract public function innerHtml();
  
  abstract public function subject();
}
