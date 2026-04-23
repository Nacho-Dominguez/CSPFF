<?php
class A25_RedirectUserToUnreadMessages
{
  private $user_id;
  private $option;
  public function __construct($user_id, $option)
  {
    $this->user_id = $user_id;
    $this->option = $option;
  }
  public function redirectIfUnreadMessages()
  {
    $unread = $this->queryForUnreadMessages();
    $this->redirectToInbox($unread);
  }
	protected function queryForUnreadMessages()
	{
    return Doctrine_Query::create()
        ->select('*')
        ->from('JosMessages m')
        ->where('m.user_id_to = ?', $this->user_id)
        ->andWhere('m.state = 0')
        ->count();
	}
  protected function redirectToInbox($unread)
  {
    // Don't redirect if already on messages page
    if ($unread && $this->option != 'com_messages')
    {
      $redirector = A25_DI::Redirector();
      $redirector->redirect('index2.php?option=com_messages',
          'Please read all unread messages before proceeding');
    }
  }
}