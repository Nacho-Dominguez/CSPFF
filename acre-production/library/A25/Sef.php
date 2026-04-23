<?php
class A25_Sef extends A25_StrictObject
{
  public static function task()
  {
    $request = new A25_Request();
    $uri = $request->uri();
    return self::parseUriForTask($uri);
  }
	public static function parseUriForTask($request_uri)
	{
		if (strpos($request_uri, '.php'))
			return false;
		
		if ('/administrator' == $request_uri || '/administrator/' == $request_uri)
			return false;

		if (preg_match("#/(([^\?/]|/[^?])+)/?(?:\?[^/]*)?$#", $request_uri, $matches))
    {
      $match = self::convertSlashesToUnderscores($matches[1]);
			return self::convertDashesToCamelCase($match);
    }

		return false;
	}
	private static function convertDashesToCamelCase($str)
	{
    $str = ucwords(str_replace(array('-', '_'), array(' ', '_ '), $str));
		return str_replace(' ', '', $str);
	}
  private static function convertSlashesToUnderscores($str)
  {
    return str_replace('/', '_', $str);
  }
}