<?php

class Controller_TermsOfService extends Controller
{
	protected function subtitle()
	{
		return 'Alive at 25 SMS Terms of Service';
	}
	
	public function executeTask()
	{
        echo '<h1>Alive at 25 SMS Messaging Terms of Service </h1>
<ol>
<li>We\'ll only send you messages related to your class, such as reminders or changes to the class location or time.</li>
<li>You can cancel at any time by replying STOP.</li>
<li>If you are experiencing issues with the messaging program you can reply with the keyword HELP for more assistance, or you can get help directly at ' . A25_DI::PlatformConfig()->contactEmailAddress . '</li>
<li>Carriers are not liable for delayed or undelivered messages</li>
<li>As always, message and data rates may apply for any messages sent to you from us and to us from you. Message frequency varies but you should receive no more than a few messages. If you have any questions about your text plan or data plan, it is best to contact your wireless provider.</li>
<li>If you have any questions regarding privacy, please read our <a href="' . A25_Link::to('/component/option,com_location/task,privacypolicy/') . '">privacy policy</a></li>
    </ol>
';
	}
}
