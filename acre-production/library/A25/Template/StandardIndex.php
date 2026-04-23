<?php

namespace Acre\A25\Template;

class StandardIndex
{
    private $header;

    public function __construct(
        HeaderInterface $header,
        FooterInterface $footer
    ) {
        $this->header = $header;
        $this->footer = $footer;
    }

    public function run($Itemid, $option, $task)
    {
        require dirname(__FILE__) . '/StandardIndex.phtml';
    }
}
