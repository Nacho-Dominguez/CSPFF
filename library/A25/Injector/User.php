<?php

class A25_Injector_User extends A25_Injector
{
	protected function defaultValue()
	{
		return A25_Record_User::retrieve(A25_DI::UserId());
	}
}
