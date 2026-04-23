<?php

class A25_Envelope
{
  private $emailContent;

  public function __construct($emailContent)
  {
    $this->emailContent = $emailContent;
  }

  public function send($address)
  {
    $subject = $this->emailContent->subject();
    $body = $this->body();
    $alt_body = $this->alt_body();

    A25_DI::Mailer()->mail($address, $subject, $body, true, $alt_body);
  }

  /**
   * @todo-scopeAndMakeIssue - There is a bunch of duplication between
   * Body.phtml and header.phtml & footer.phtml.
   */
  protected function body()
  {
    ob_start();
    require dirname(__FILE__) . '/Body.phtml';
    return ob_get_clean();
  }

  protected function alt_body()
  {
    $body = $this->body();
    $converter = new A25_HtmlTextConverter();
    return $converter->wrapText($converter->stripHtml($body));
  }
}
