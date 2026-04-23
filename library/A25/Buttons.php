<?php
class A25_Buttons
{
	public static function toolbar($label, $querystring, $imgFileName)
	{
		return  self::toolbarWithUnassumingUrl($label,
				A25_Link::to("/administrator/index2.php?$querystring"),
				$imgFileName);
	}
	
	public static function toolbarWithUnassumingUrl($label, $url, $imgFileName)
	{
		return  "<a href='" . $url . "'>"
			. '<div style="text-align: center; border: 1px solid #cfcfcf; '
					. 'width: 60px; color: #8f8f8f; margin-right: 4px;">'
			. "<img src='/administrator/images/$imgFileName' border=0 />"
			. "<br/>$label</div></a>";
	}
}