<?php

class A25_Injector_Hasher extends A25_Injector
{
	protected function defaultValue()
	{
		return new A25_Hasher();
	}
}
