<?php

class A25_Injector_Factory extends A25_Injector
{
	protected function defaultValue()
	{
    if (A25_DI::PlatformConfig()->courseIsOnline)
      return new A25_Factory_Online();
    else
      return new A25_Factory_PhysicalLocation();
	}
}
