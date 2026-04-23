<?php

namespace Acre\A25\Template;

class NoTopMenuContainer implements TopMenuContainerInterface
{
    private $topMenu;
    public function __construct(TopMenuInterface $topMenu)
    {
        $this->topMenu = $topMenu;
    }
    public function run()
    {
        return '';
    }
}