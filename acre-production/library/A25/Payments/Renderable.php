<?php

namespace Acre\A25\Payments;

class Renderable
{
    protected $output;
    protected $heading;
    protected $footer = '</div>';

    public function heading()
    {
        return $this->heading;
    }

    public function output()
    {
        return $this->output;
    }

    public function footer()
    {
        return $this->footer;
    }
}
