<?php

namespace Acre\A25\Template;

// For mosLoadModules(), which is used in StandardFooter.phtml
require_once dirname(__FILE__) . '/../../../includes/frontend.php';

class StandardFooter implements FooterInterface
{
    private $tracker;
    public function __construct(TrackerInterface $tracker)
    {
        $this->tracker = $tracker;
    }

    public function run()
    {
        ob_start();
        require dirname(__FILE__) . '/StandardFooter.phtml';
        return ob_get_clean();
    }

    public function fireAppendFooterMenu()
    {
        foreach (\A25_ListenerManager::all() as $listener) {
            if ($listener instanceof \A25_ListenerI_AppendFooterMenu) {
                return $listener->appendFooterMenu();
            }
        }
    }
}
