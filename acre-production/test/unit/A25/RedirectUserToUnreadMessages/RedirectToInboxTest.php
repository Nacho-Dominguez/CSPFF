<?php
require_once(dirname(__FILE__) . '/../../../../autoload.php');

class test_unit_A25_RedirectUserToUnreadMessages_RedirectToInboxTest
    extends test_Framework_UnitTestCase
{
  private $user_id;
  public function setUp()
  {
    parent::setUp();
    $this->user_id = 1;
    $redirector = $this->mock('A25_Redirector');
    A25_DI::setRedirector($redirector);
  }
	/**
	 * @test
	 */
	public function redirectsIfUnreadMessages()
	{
    $option = '';
    $unread = 1;
    $redirect = new RedirectUserToUnreadMessagesWithRedirectToInboxExposed($this->user_id, $option);
    A25_DI::Redirector()->expects($this->once())->method('redirect');
    $redirect->redirectToInbox($unread);
	}
	/**
	 * @test
	 */
	public function doesNotRedirectIfNoUnreadMessages()
	{
    $option = '';
    $unread = 0;
    $redirect = new RedirectUserToUnreadMessagesWithRedirectToInboxExposed($this->user_id, $option);
    A25_DI::Redirector()->expects($this->never())->method('redirect');
    $redirect->redirectToInbox($unread);
	}
	/**
	 * @test
	 */
	public function doesNotRedirectIfOnMessagePage()
	{
    $option = 'com_messages';
    $unread = 1;
    $redirect = new RedirectUserToUnreadMessagesWithRedirectToInboxExposed($this->user_id, $option);
    A25_DI::Redirector()->expects($this->never())->method('redirect');
    $redirect->redirectToInbox($unread);
	}
}

class RedirectUserToUnreadMessagesWithRedirectToInboxExposed
    extends A25_RedirectUserToUnreadMessages
{
  public function redirectToInbox($unread) {
    return parent::redirectToInbox($unread);
  }
}
