<?php
class A25_Allow
{
	public static function administratorOrHigher()
	{
		if (!A25_DI::User()->isAdminOrHigher())
		{
			A25_DI::Redirector()->redirect('index2.php',
					'Sorry, you are not authorized to do that.');
		}
	}

	public static function superAdmin()
	{
		if (!A25_DI::User()->isSuperAdmin())
				A25_DI::Redirector()->redirect( 'index2.php',
						'You are not authorized to do that.' );
	}
  
	public static function everyoneExceptCourtAdmin()
	{
		if (A25_DI::User()->isCourtAdministrator())
				A25_DI::Redirector()->redirect( 'index2.php',
						'You are not authorized to do that.' );
	}
}
?>
