<?php

class A25_Plugin_Resources implements A25_ListenerI_AddIcons
{
	public function afterAdminButtons()
	{
    // Court admins are not allowed to see Resources
    if (A25_DI::User()->isCourtAdministrator())
      return;
		
    $link = 'resources';
    quickiconButton($link, 'documents.png', 'Employee Resources');
	}
}

set_include_path(
	ServerConfig::webRoot . '/plugins/Resources' . PATH_SEPARATOR
	. get_include_path()
);
