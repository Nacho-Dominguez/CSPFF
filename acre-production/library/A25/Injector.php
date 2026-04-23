<?php

abstract class A25_Injector
{
	private $_value;

	public function getValue()
	{
		if (!$this->_value) {
			$this->_value = $this->defaultValue();
		}
		return $this->_value;
	}

	public function setValue($value)
	{
		$this->_value = $value;
	}

	protected abstract function defaultValue();
}
