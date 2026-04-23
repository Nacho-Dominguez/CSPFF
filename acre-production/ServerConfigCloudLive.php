<?php

require_once(dirname(__FILE__) . '/ServerConfigCloud.php');

abstract class ServerConfigCloudLive extends ServerConfigCloud {
    const arePaymentsLive = true;

	static public function setErrorReporting()
	{
		parent::setErrorReporting();
		ini_set('display_errors',false);
	}

}
