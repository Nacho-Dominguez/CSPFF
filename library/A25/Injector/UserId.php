<?php

class A25_Injector_UserId extends A25_Injector
{
	protected function defaultValue()
	{
		global $my;
		return $my->id;
	}
}
