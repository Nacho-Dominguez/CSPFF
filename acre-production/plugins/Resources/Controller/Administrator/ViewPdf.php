<?php

class Controller_Administrator_ViewPdf extends Controller
{
	public function executeTask()
	{
    $pdf_path = $_GET['uri'];
    $return_path = A25_Link::to('/administrator/resources');
		require dirname(__FILE__) . '/ViewPdf.phtml';
	}
}


