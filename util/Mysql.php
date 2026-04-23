<?php

class util_Mysql
{
	public static $disableModifications = false;
  
	static public function executeSqlScript($sqlScriptPath, $db, $host, $user,
			$pass)
	{
    if (self::$disableModifications)
      throw new Exception(
          'Mysql modifications have been disabled. You cannot modify the database in a unit test.');
    
		$file = @file_get_contents($sqlScriptPath);
		if (!$file)
			throw new Exception ("File not found: $sqlScriptPath");

		$conn = self::_createDbConnection($db,$host,$user,$pass);
		// Apparently, it is necessary to assign the return value of exec() to 
		// a variable if you want the program to wait for exec() to finish before
		// continuing.
		$nothing = $conn->query($file);

		$conn = null;	// Closes connection and frees memory
	}

	static private function _createDbConnection ($db, $host, $user, $pass)
	{
		$conn = new PDO(
			'mysql:dbname=' . $db . ';host=' . $host,
			$user,
			$pass,
			array (PDO::ATTR_PERSISTENT => true)
		);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}
}
