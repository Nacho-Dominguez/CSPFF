<?php

class A25_Include_Tooltip extends A25_StrictObject
{
	private static $javascript_already_added = false;

	public function load()
	{
		if (self::$javascript_already_added)
			return;
    
    $head = A25_DI::HtmlHead();
    $head->includeJqueryUI();
		
    ob_start();
    
    require dirname(__FILE__) . '/tooltip.phtml';
		
    $javascript = ob_get_clean();
		
		$head->append($javascript);
		
		self::$javascript_already_added = true;
	}
}