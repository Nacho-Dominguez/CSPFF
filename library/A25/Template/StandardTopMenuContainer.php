<?php

namespace Acre\A25\Template;

class StandardTopMenuContainer implements TopMenuContainerInterface
{
    private $topMenu;
    public function __construct(TopMenuInterface $topMenu)
    {
        $this->topMenu = $topMenu;
    }
    public function run()
    {
        require dirname(__FILE__) . '/StandardTopMenuContainer.phtml';
    }
}