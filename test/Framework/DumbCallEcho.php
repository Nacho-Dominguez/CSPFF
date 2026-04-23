<?php

namespace Acre\TestFramework;

class DumbCallEcho
{
    public $callLog = "";
    public function __call($method, $args)
    {
        ob_start();
        var_dump($args);
        $argDetails = ob_get_clean();
        $this->callLog .= "Method: $method. Args: $argDetails\n";
    }
}
