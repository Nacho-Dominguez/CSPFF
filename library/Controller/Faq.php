<?php

class Controller_Faq extends Controller
{
	protected function subtitle()
	{
		return 'FAQ';
	}
	
	public function executeTask()
	{
		if(PlatformConfig::isNationalPortal)
			A25_DI::Redirector()->redirect(A25_Link::to(PlatformConfig::faqUrl(), '', 301));

		$_REQUEST['Itemid'] = 44;
    
    $faq = new A25_Page_Faq();
    $faq->display();
	}
}
