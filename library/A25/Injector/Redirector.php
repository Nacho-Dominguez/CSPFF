<?php

class A25_Injector_Redirector extends A25_Injector
{
	protected function defaultValue()
	{
		return new A25_Redirector();
	}
}
