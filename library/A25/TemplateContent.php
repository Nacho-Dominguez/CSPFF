<?php
class A25_TemplateContent
{
	public static function titleAndLogo()
	{
		return self::retrieve('titleAndLogo.php');
	}
	/**
	 * @todo-soon: remove duplication with PlatformConfig::openPlatformTemplate()
	 */
	private static function retrieve($filename)
	{
		$default = self::pathToTemplateNamed('aliveat25')
				. '/content/' . $filename;
		$override = self::pathToTemplateNamed($GLOBALS['cur_template'])
				. '/content/' . $filename;

		ob_start();
		if (file_exists($override))
			include $override;
		else
			include $default;
		return ob_get_clean();
	}
	private static function pathToTemplateNamed($name)
	{
		return ServerConfig::webRoot . '/templates/' . $name;
	}
}
