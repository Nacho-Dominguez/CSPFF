<?php
/**
 * Many of the objects managed by this class should be created using
 * A25_Factory instead.  Generally, only singletons should be managed here.
 * 
 * @todo-refactor_di:
 * Instead of defining the public functions such as DB() and setDB(), use
 * __callStatic() to figure out which object to use. For example, calling
 * A25_DI::DB() would use __callStatic(), which would automatically call
 * $this->_[function name, which is DB]->getValue().
 *
 * This change can not be implemented until we use php 5.3
 */
class A25_DI
{
	private static $_DB;
	private static $_Factory;
  private static $_Hasher;
	private static $_HtmlHead;
	private static $_Mailer;
  private static $_PlatformConfig;
	private static $_Redirector;
  private static $_ServerConfig;
	private static $_User;
	private static $_UserId;
	private static $_QueryString;

	public static function reset()
	{
		self::$_DB = new A25_Injector_DB();
		self::$_Factory = new A25_Injector_Factory();
		self::$_HtmlHead = new A25_Injector_HtmlHead();
    self::$_Hasher = new A25_Injector_Hasher();
		self::$_Mailer = new A25_Injector_Mailer();
    self::$_PlatformConfig = new A25_Injector_PlatformConfig();
    self::$_ServerConfig = new A25_Injector_ServerConfig();
		self::$_Redirector = new A25_Injector_Redirector();
		self::$_User = new A25_Injector_User();
		self::$_UserId = new A25_Injector_UserId();
		self::$_QueryString = new A25_Injector_QueryString();
	}
	
	/**
	 * @return database
	 */
	public static function DB()
	{
		return self::$_DB->getValue();
	}
	public static function setDB(database $value)
	{
		self::$_DB->setValue($value);
	}
	
	/**
	 * @return A25_Factory
	 */
	public static function Factory()
	{
		return self::$_Factory->getValue();
	}
	public static function setFactory(A25_Factory $value)
	{
		self::$_Factory->setValue($value);
	}
	
	/**
	 * @return A25_HtmlHead
	 */
	public static function HtmlHead()
	{
		return self::$_HtmlHead->getValue();
	}
	public static function setHtmlHead(A25_HtmlHead $value)
	{
		self::$_HtmlHead->setValue($value);
	}
  
	/**
	 * @return A25_Hasher
	 */
	public static function Hasher()
	{
		return self::$_Hasher->getValue();
	}
	public static function setHasher(A25_Hasher $value)
	{
		self::$_Hasher->setValue($value);
	}
	
	/**
	 * @return A25_Mailer
	 */
	public static function Mailer()
	{
		return self::$_Mailer->getValue();
	}
	public static function setMailer(A25_Mailer $value)
	{
		self::$_Mailer->setValue($value);
	}
  
	/**
	 * @return PlatformConfig
	 */
	public static function PlatformConfig()
	{
		return self::$_PlatformConfig->getValue();
	}
	public static function setPlatformConfig(
			PlatformConfig $value)
	{
		self::$_PlatformConfig->setValue($value);
	}
  
	/**
	 * @return A25_ServerConfig
	 */
	public static function ServerConfig()
	{
		return self::$_ServerConfig->getValue();
	}
	public static function setServerConfig(
			ServerConfig $value)
	{
		self::$_ServerConfig->setValue($value);
	}

	/**
	 * @return array
	 */
	public static function QueryString()
	{
		return self::$_QueryString->getValue();
	}
	public static function setQueryString(array $value)
	{
		self::$_QueryString->setValue($value);
	}

	/**
	 * @return A25_Redirector
	 */
	public static function Redirector()
	{
		return self::$_Redirector->getValue();
	}
	public static function setRedirector(A25_Redirector $value)
	{
		self::$_Redirector->setValue($value);
	}

	/**
	 * @return A25_Record_User
	 */
	public static function User()
	{
		return self::$_User->getValue();
	}
	public static function setUser(A25_Record_User $value)
	{
		self::$_User->setValue($value);
	}

	public static function UserId()
	{
		return self::$_UserId->getValue();
	}
	public static function setUserId($value)
	{
		self::$_UserId->setValue($value);
	}
}

A25_DI::reset();