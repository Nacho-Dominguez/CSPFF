<?php

class A25_Injector_QueryString extends A25_Injector
{
	protected function defaultValue()
	{
		return $_GET;
	}
}
