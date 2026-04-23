<?php

namespace Acre\A25\Template;

class StandardTopMenu implements TopMenuInterface
{
    public function topMenu()
    {
        $menuItems = \A25_DI::PlatformConfig()->topMenu();
        $return = '';
        foreach($menuItems as $menuItem)
        {
            $return .= self::topMenuLink($menuItem);
        }
        return $return;
    }
  
    private static function topMenuLink(array $menuItem)
    {
        return '<div><a href="' . $menuItem[0] . '" ' . self::menuClass($menuItem[1]) . '>' . $menuItem[2] . '</a></div>';
    }
  
	private static function menuClass($itemId)
	{
		if ($_REQUEST['Itemid'] == $itemId)
			   return 'id="active_menu-nav"';
		   else
			   return 'class="mainlevel-nav"';
	}
}