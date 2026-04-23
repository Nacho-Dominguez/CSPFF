<?php

/**
 * If you extend this class, any accesses to properties that don't exist will
 * result in an exception.  This can be very handy for catching typos.
 */
abstract class A25_StrictObject
{
	public function __get($name)
	{
		self::throwPropertyException($name, $this);
	}
	public function __set($name,$value)
	{
		self::throwPropertyException($name, $this);
	}
	public static function throwPropertyException($name, $object)
	{
		throw new Exception(
			sprintf('Unknown property "%s" on "%s"', $name, get_class($object)));
	}
}
?>
