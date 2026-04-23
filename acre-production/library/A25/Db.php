<?php
require_once(dirname(__FILE__) . '/../../includes/database.php');

class A25_Db extends database
{
	function __construct($host='localhost', $user, $pass, $db='',
			$table_prefix='jos_')
	{
		$this->database($host, $user, $pass, $db, $table_prefix);
    }
	
	/**
     * Executes the set query.  This version throws an Exception if the query is
     * not successful, unlike the parent version
     *
     * @throws Exception on unsuccessful query
     * @return mixed A database resource if successful, FALSE if not.
     */
	public function query()
	{
		$obj = parent::query();
		if (!$obj && $this->_errorMsg)
			throw new Exception ($this->_errorMsg);
		return $obj;
    }
	
	/**
     * Sets and executes a query.
     * 
     * @param string $sql 
     *
     * @throws Exception on unsuccessful query
     * @return mixed A database resource if successful, FALSE if not.
     */
	public function executeQuery($sql)
	{
		$this->setQuery($sql);
		$result = $this->query();
		return $result;
    }

	/**
	 * Returns the results of a 1-column SELECT statement as an array.
	 *
	 * @param string $sql - A SQL statement which SELECTS only 1 column.
	 */
	public function queryAsArray($sql)
	{
		$this->setQuery($sql);
		return $this->loadResultArray();
	}

	/**
	 * This was copied from the parent database object.  The only difference is
	 * that is uses a local mosBindArrayToObject function, instead of a global
	 * function.  If any other changes are made, this should be put under test.
	 * 
	 * @param stdClass $object
	 * @return bool 
	 */
	public function loadObject( &$object ) {
		if ($object != null) {
			if (!($cur = $this->query())) {
				return false;
			}
			if ($array = mysql_fetch_assoc( $cur )) {
				mysql_free_result( $cur );
				$this->mosBindArrayToObject( $array, $object, null, null, false );
				return true;
			} else {
				return false;
			}
		} else {
			if ($cur = $this->query()) {
				if ($object = mysql_fetch_object( $cur )) {
					mysql_free_result( $cur );
					return true;
				} else {
					$object = null;
					return false;
				}
			} else {
				return false;
			}
		}
	}

	/**
	 * Copy the named array content into the object as properties
	 * only existing properties of object are filled. when undefined in hash,
	 * properties wont be deleted.
	 *
	 * This function was copied word-for-word from /includes/joomlaClasses.php.
	 * It was copied because other functions in this class need it, but we don't
	 * want a global dependency.
	 *
	 * Put this function under test if any changes are to be made.
	 *
	 * @param array the input array
	 * @param obj byref the object to fill of any class
	 * @param string
	 * @param boolean
	 */
	private function mosBindArrayToObject( $array, &$obj, $ignore='',
			$prefix=NULL, $checkSlashes=true )
	{
		if (!is_array( $array ) || !is_object( $obj )) {
			return (false);
		}

		foreach (get_object_vars($obj) as $k => $v) {
			if( substr( $k, 0, 1 ) != '_' ) {			// internal attributes of an object are ignored
				if (strpos( $ignore, $k) === false) {
					if ($prefix) {
						$ak = $prefix . $k;
					} else {
						$ak = $k;
					}
					if (isset($array[$ak])) {
						$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? mosStripslashes( $array[$ak] ) : $array[$ak];
					}
				}
			}
		}

		return true;
	}

	/**
	 * Strip slashes from strings or arrays of strings.
	 *
	 * This function was copied word-for-word from /includes/joomlaClasses.php.
	 * It was copied because other functions in this class need it, but we don't
	 * want a global dependency.
	 *
	 * Put this function under test if any changes are to be made.
	 *
	 * @param mixed The input string or array
	 * @return mixed String or array stripped of slashes
	 */
	private function mosStripslashes( &$value )
	{
		$ret = '';
		if (is_string( $value )) {
			$ret = stripslashes( $value );
		} else {
			if (is_array( $value )) {
				$ret = array();
				foreach ($value as $key => $val) {
					$ret[$key] = mosStripslashes( $val );
				}
			} else {
				$ret = $value;
			}
		}
		return $ret;
	}
}
?>
