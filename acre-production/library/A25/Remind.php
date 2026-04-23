<?php

/**
 * @todo-scopeAndMakeIssue - since some of the subclasses of this, such as
 * A25_Remind_Students_DonationReceipt are not actually "reminders", we should
 * rename this to something more appropriate.
 */
abstract class A25_Remind
{
  public function send()
	{
		return $this->sendTo($this->whom());
	}
  
	protected function sendTo($recipients)
	{
		$count = 0;
		foreach($recipients as $recipient) {
      $this->sendToIndividual($recipient);
			$count++;
		}
		return $count;
	}
  
  protected abstract function sendToIndividual($recipient);
  
  protected abstract function whom();
}