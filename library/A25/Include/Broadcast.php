<?php

/**
 * @todo-after-php53 -- extract the common reusable code into a superclass.  We
 * can't do this until we have PHP 5.3, because it allows smarter static
 * polymorphism.
 * 
 * @testing - just test by hand, because automated string output tests are
 * fragile.  For Broadcast, there is also a selenium test that will not pass if
 * this doesn't get loaded correctly.
 */
class A25_Include_Broadcast extends A25_StrictObject
{
	private static $javascript_already_added = false;

	public function load()
	{
		if (self::$javascript_already_added)
			return;
		
    ob_start();
    
    require dirname(__FILE__) . '/broadcast.phtml';
		
    $javascript = ob_get_clean();
		
		A25_DI::HtmlHead()->append($javascript);
		
		self::$javascript_already_added = true;
	}
}