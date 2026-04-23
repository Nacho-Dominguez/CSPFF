<?php

class A25_HtmlTextConverter
{
  public function stripHtml($text)
  {
    $text = strip_tags($text, '<p><div><br><br/><h1><h2><h3><h4><h5><h6>');
    $text = preg_replace('/\s+/', ' ', $text); // strip multiple whitespace characters
    $text = preg_replace('#</?div.*?>(?:\s*</?div.*?>)*#i', "\n", $text); // replace div tags with new line
    $text = preg_replace('#</?p.*?>#i', "\n", $text); // replace p tags with new line
    $text = preg_replace('#<br\s?/?>#i', "\n", $text); // replace br tags with new line
    $text = preg_replace('#<h\d.*?>#i', "\n\n", $text); // replace opening h tags with two new lines
    $text = preg_replace('#</h\d>#i', "\n", $text); // replace closing h tags with new line
    $text = preg_replace('/^[^\S\n]+/m', '', $text); // remove whitespace at the beginning of a line
    $text = preg_replace('/\n{4,}/', "\n\n\n", $text); // replace 4 or more new lines with 3 new lines
    $text = str_replace('&ndash;', '-', $text);
    return $text;
  }
  
  public function wrapText($text)
  {
    $text = wordwrap($text, 78);
    return $text;
  }
}
