<?php

namespace Acre\A25\Template;

class AliveAt25UsHeader implements HeaderInterface
{
    private $topMenuContainer;

    public function __construct(TopMenuContainerInterface $topMenuContainer)
    {
        $this->topMenuContainer = $topMenuContainer;
    }

    public function run()
    {
        ob_start();
        require dirname(__FILE__) . '/AliveAt25UsHeader.phtml';
        return ob_get_clean();
    }

    public function fireAfterState()
    {
        foreach (\A25_ListenerManager::all() as $listener) {
            if ($listener instanceof \A25_ListenerI_BannerAd) {
                return $listener->afterState();
            }
        }
    }
}
