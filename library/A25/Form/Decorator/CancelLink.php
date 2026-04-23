<?php

class A25_Form_Decorator_CancelLink extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
		return $content . '&nbsp; &nbsp;<a href="javascript:void()" onClick="history.go(-1)">Cancel</a>';
    }
}
