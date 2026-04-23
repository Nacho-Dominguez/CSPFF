<?php

require_once(dirname(__FILE__) . '/ServerConfig.php');


ServerConfig::setErrorReporting();

set_include_path(
		ServerConfig::webRoot . PATH_SEPARATOR
		. ServerConfig::webRoot . '/custom' . PATH_SEPARATOR
		. ServerConfig::webRoot . '/third-party/ZendFramework/library'
		. PATH_SEPARATOR
		. ServerConfig::webRoot . '/third-party/doctrine'
		. PATH_SEPARATOR . ServerConfig::webRoot . '/third-party/phpunit'
		. PATH_SEPARATOR . ServerConfig::webRoot . '/library'
		. PATH_SEPARATOR . ServerConfig::webRoot . '/library/A25/Record/Doctrine'
		. PATH_SEPARATOR . ServerConfig::webRoot . '/library/A25/Record/Doctrine/generated'
		. PATH_SEPARATOR . ServerConfig::phpIncludePath);

/**
 * This will autoload classes using the Pear convention of '_' being a '/' in
 * the filesystem.  The root is WEBROOT/test/unit/.
 */
function autoloadFunction($classname)
{
  // Do not look for Twilio's classes.  Twilio has its own autoload function.
  if (substr($classname, 0, 15) == 'Services_Twilio') {
      return false;
  }
    
	// name your classes and filenames with underscores, i.e., Net_Whois stored
	// in Net/Whois.php
	$classfile = str_replace("_", "/", $classname) . ".php";
  // Check if the file exists before trying to include it
  if (!file_with_include_path($classfile)) {
      return false;
  }
	return include $classfile;
}
spl_autoload_register('autoloadFunction');

// Use Composer's autoloader for namespaced classes:
require_once(dirname(__FILE__) . '/vendor/autoload.php');

/*
 * After we update to php 5.3.2, instead of this function we can just use
 * stream_resolve_include_path().
 */
function file_with_include_path($file)
{
  $paths = explode(PATH_SEPARATOR, get_include_path());
  $found = false;
  foreach($paths as $p) {
    $fullname = $p . DIRECTORY_SEPARATOR . $file;
    if(is_file($fullname)) {
      $found = $fullname;
      break;
    }
  }
  return $found;
}

function assert_failure()
{
	throw new Exception('failed assertion');
}
assert_options(ASSERT_CALLBACK, 'assert_failure');


function bootstrapDoctrine()
{
	/**
	 * This sets up a Doctrine connection.  It is lazy-loaded, which means it won't
	 * actually connect if it isn't used.
	 */
	Doctrine_Manager::connection(new PDO(
		'mysql:dbname=' . ServerConfig::dbName . ';host=' . ServerConfig::dbHost,
		ServerConfig::dbUser,
		ServerConfig::dbPassword
	));

	$manager = Doctrine_Manager::getInstance();
	$manager->setAttribute(Doctrine::ATTR_MODEL_LOADING,
			Doctrine::MODEL_LOADING_CONSERVATIVE);
	$manager->setAttribute(Doctrine::ATTR_PORTABILITY,
			Doctrine::PORTABILITY_EMPTY_TO_NULL);
	$manager->setAttribute(Doctrine_Core::ATTR_QUERY_CLASS, 'A25_Query');
	$manager->setAttribute(Doctrine_Core::ATTR_HYDRATE_OVERWRITE, false);
}

bootstrapDoctrine();

// Load plugins
A25_ListenerManager::startUp();
