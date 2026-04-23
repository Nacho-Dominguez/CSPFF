<?php

class A25_Injector_DB extends A25_Injector
{
	protected function defaultValue()
	{
		global $database;
		return $database;
	}
}
