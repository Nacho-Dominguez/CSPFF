<?php

class A25_Injector_Mailer extends A25_Injector
{
	protected function defaultValue()
	{
		return new A25_Mailer();
	}
}
