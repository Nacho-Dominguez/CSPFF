<?php

/**
 * @todo-after-php53 -- extract the common reusable code into a superclass.  We
 * can't do this until we have PHP 5.3, because it allows smarter static
 * polymorphism.
 * 
 * @testing - just test by hand, because automated string output tests are
 * fragile.
 */
class A25_Include_1140CssGrid extends A25_StrictObject
{
	private static $javascript_already_added = false;

	public function load()
	{
		if (self::$javascript_already_added)
			return;
    
    A25_DI::HtmlHead()->stylesheet(
        '/includes/third-party/1140_CssGrid_2/css/1140.css');
    A25_DI::HtmlHead()->javascriptFile(
        '/includes/third-party/1140_CssGrid_2/js/css3-mediaqueries.js');
    A25_DI::HtmlHead()->append('<!--[if lte IE 9]><link rel="stylesheet" href="'
        . ServerConfig::currentUrl()
        . '/includes/third-party/1140_CssGrid_2/css/ie.css" type="text/css" media="screen" /><![endif]-->');
		
		self::$javascript_already_added = true;
	}
}