<?php

class A25_Link
{
	function __construct()
	{
	}

	/**
	 * Creates a link to the path on the current server.  Also removes 
	 * double slashes '//'.
	 */
	public static function to($path)
	{
		$url = ServerConfig::currentUrl() . '/' . $path;
		$url = self::convertToSEF($url);
		return self::removeDoubleSlashes($url);
	}
	public static function toNational($path)
	{
		$url = ServerConfig::nationalUrl . '/' . $path;
		return self::removeDoubleSlashes($url);
	}
	public static function withoutSef($path)
	{
		$url = ServerConfig::currentUrl() . '/' . $path;
		$url = self::encodeAmpersands($url);
		return self::removeDoubleSlashes($url);
	}
	public static function encodeAmpersands($url)
	{
		return preg_replace('/&/', '&amp;', $url);
	}
	public static function withJavascriptConfirmation($path, $message)
	{
		return "javascript:if(confirm('$message')) location='$path'";
	}
	public static function https($path)
	{
		$url = ServerConfig::staticHttpsUrl() . '/' . $path;
		$url = self::convertToSEF($url);
		return self::removeDoubleSlashes($url);
	}
	public static function removeDoubleSlashes($url)
	{
		$url = preg_replace('#//+#','/',$url);
		$url = preg_replace('#http:/#','http://',$url);
		$url = preg_replace('#https:/#','https://',$url);
		return $url;
	}
	public static function convertToSEF($url)
	{
		if (preg_match('#administrator/#',$url))
			return $url;
		if (preg_match('/option=com_content/', $url)) {
			$url = preg_replace('/(?:\?|&amp;|&)option=com_content/','',$url);
			$url = preg_replace('/(?:\?|&amp;|&)task=view/','view/',$url);
			$url = preg_replace('/(?:\?|&amp;|&)id=(\d+)/','$1/',$url);
			$url = preg_replace('/(?:\?|&amp;|&)Itemid=(\d+)/','$1/',$url);
			$url = preg_replace('#[^/]+\.php#','content/',$url);
		}
		if (preg_match('/option=com_/',$url)) {
			$url = preg_replace('#[^/]+\.php#','component/',$url);
		}
		$url = preg_replace('/(?:\?|&amp;|&)([^=]+)=([^&]*)/','$1,$2/',$url);
		return $url;
	}
}
?>
