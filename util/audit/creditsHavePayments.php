<?php

require_once dirname(__FILE__) . '/../../autoload.php';

$credits = Doctrine_Query::create()
		->from('A25_Record_Credit c')
		->where('c.pay_id = 0')->execute();

if ($credits->count() > 0) {
	A25_DI::Mailer()->mail('jonathan@appdevl.net',
					ServerConfig::staticHttpUrl() . ' Data Audit Failure',
					'There are credits which do not have pay_id set');
	exit(1);
}
exit(0);
