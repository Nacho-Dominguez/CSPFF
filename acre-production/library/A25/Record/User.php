<?php
require_once(dirname(__FILE__).'/../../../includes/gacl_api.class.php');
/**
 * This class extends A25_Record_User, adding represention of the relationships between
 * an administrative user and the elements of the domain.
 */
class A25_Record_User extends JosUsers
{

	private $_acl;

	protected $_courts;
	// A boolean that indicates whether the courts have been loaded from the
	// database.  It helps the class act a little bit like a GOF proxy.
	protected $_courtsAreNotLoaded = true;

	public function construct()
	{
		global $acl;
		if(!$acl) {
-           $acl = new gacl_api(A25_DI::DB());
		}
-       $this->_acl = $acl;
	}

	/**
	 * @param integer $id
	 * @return A25_Record_StudentMessage
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_User')->find($id);
    }

	public function isAdminOrHigher()
	{
		return ($this->isSuperAdmin() || $this->isAdmin());
	}
	public function isLocationAdminOrHigher()
	{
		return ($this->isAdminOrHigher() || $this->isLocationAdministrator());
	}
	public function isSuperAdmin()
	{
		return ($this->usertype == 'Super Administrator');
	}
	public function isAdmin()
	{
		return ($this->usertype == 'Administrator');
	}
	public function isLocationAdministrator()
	{
		return ($this->usertype == 'Location Administrator');
	}
	public function isCourtAdministrator()
	{
		return ($this->usertype == 'Court Administrator');
	}
	public function isInstructor()
	{
		return ($this->usertype == 'Instructor');
	}

	public function id()
	{
		return $this->id;
	}
	
	public function getSelectionName()
	{
		return $this->name;
	}

	/**
	 * Validation and filtering
	 * @return boolean True is satisfactory
	 */
	function check() {
		global $mosConfig_uniquemail;

		// Validate user information
		if (trim( $this->name ) == '') {
			$this->_error = _REGWARN_NAME;
			return false;
		}

		if (trim( $this->username ) == '') {
			$this->_error = _REGWARN_UNAME;
			return false;
		}

		// check that username is not greater than 25 characters
		$username = $this->username;
		if ( strlen($username) > 40 ) {
			$this->username = substr( $username, 0, 40 );
		}

		// check that password is not greater than 50 characters
		$password = $this->password;
		if ( strlen($password) > 50 ) {
			$this->password = substr( $password, 0, 50 );
		}

		if (eregi( "[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|]", $this->username) || strlen( $this->username ) < 3) {
			$this->_error = sprintf( _VALID_AZ09, _PROMPT_UNAME, 2 );
			return false;
		}

		//if ((trim($this->email == "")) || (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $this->email )==false)) {
		if ((trim($this->email != "")) && (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $this->email )==false)) {
			$this->_error = _REGWARN_MAIL;
			return false;
		}

		// check for existing username
		$query = "SELECT id"
		. "\n FROM #__users "
		. "\n WHERE username = '$this->username'"
		. "\n AND id != " . (int)$this->id
		;
		A25_DI::DB()->setQuery( $query );
		$xid = intval( A25_DI::DB()->loadResult() );
		if ($xid && $xid != intval( $this->id )) {
			$this->_error = _REGWARN_INUSE;
			return false;
		}

		if ($mosConfig_uniquemail) {
			// check for existing email
			$query = "SELECT id"
			. "\n FROM #__users "
			. "\n WHERE email = '$this->email'"
			. "\n AND id != " . (int)$this->id
			;
			A25_DI::DB()->setQuery( $query );
			$xid = intval( A25_DI::DB()->loadResult() );
			if ($xid && $xid != intval( $this->id )) {
				$this->_error = _REGWARN_EMAIL_INUSE;
				return false;
			}
		}

		return true;
	}

	public function save() {
		global $migrate;
		$acl = $this->_acl;
		$section_value = 'users';

		$k = $this->getPrimaryKeyFieldName();
		$key =  $this->$k;
		if( $key && !$migrate) {
			// existing record
			parent::save();
			// syncronise ACL
			// single group handled at the moment
			// trivial to expand to multiple groups
			$groups = $acl->get_object_groups( $section_value, $this->$k, 'ARO' );
			$acl->del_group_object( $groups[0], $section_value, $this->$k, 'ARO' );
			$acl->add_group_object( $this->gid, $section_value, $this->$k, 'ARO' );

			$object_id = $acl->get_object_id( $section_value, $this->$k, 'ARO' );
			$acl->edit_object( $object_id, $section_value, A25_DI::DB()->getEscaped( $this->name ), $this->$k, 0, 0, 'ARO' );
		} else {
			parent::save();
			// syncronise ACL
			$acl->add_object( $section_value, A25_DI::DB()->getEscaped( $this->name ), $this->$k, null, null, 'ARO' );
			$acl->add_group_object( $this->gid, $section_value, $this->$k, 'ARO' );
		}
		return true;
	}

	function delete( $oid=null ) {
		$acl = $this->_acl;

		$k = $this->getPrimaryKeyFieldName();
		if ($oid) {
			$this->$k = intval( $oid );
		}
		$aro_id = $acl->get_object_id( 'users', $this->$k, 'ARO' );
//		$acl->del_object( $aro_id, 'ARO', true );

		$query = "DELETE FROM ".$this->getTableName()
		. "\n WHERE ".$this->getPrimaryKeyFieldName()." = '".$this->$k."'";
		
		A25_DI::DB()->setQuery( $query );

		if (A25_DI::DB()->query()) {
			// cleanup related data

			// :: private messaging
			$query = "DELETE FROM #__messages_cfg"
			. "\n WHERE user_id = ". $this->$k .""
			;
			A25_DI::DB()->setQuery( $query );
			if (!A25_DI::DB()->query()) {
				$this->_error = A25_DI::DB()->getErrorMsg();
				return false;
			}
			$query = "DELETE FROM #__messages"
			. "\n WHERE user_id_to = ". $this->$k .""
			;
			A25_DI::DB()->setQuery( $query );
			if (!A25_DI::DB()->query()) {
				$this->_error = A25_DI::DB()->getErrorMsg();
				return false;
			}

			return true;
		} else {
			$this->_error = A25_DI::DB()->getErrorMsg();
			$this->_error = $query;
			return false;
		}
	}

	/**
	 * Gets the users from a group
	 * @param string The value for the group (not used 1.0)
	 * @param string The name for the group
	 * @param string If RECURSE, will drill into child groups
	 * @param string Ordering for the list
	 * @return array
	 */
	function getUserListFromGroup( $value, $name, $recurse='NO_RECURSE', $order='name' ) {
		$acl = $this->_acl;

		// Change back in
		//$group_id = $acl->get_group_id( $value, $name, $group_type = 'ARO');
		$group_id = $acl->get_group_id( $name, $group_type = 'ARO');
		$objects = $acl->get_group_objects( $group_id, 'ARO', 'RECURSE');

		if (isset( $objects['users'] )) {
			$gWhere = '(id =' . implode( ' OR id =', $objects['users'] ) . ')';

			$query = "SELECT id AS value, name AS text"
			. "\n FROM #__users"
			. "\n WHERE block = '0'"
			. "\n AND " . $gWhere
			. "\n ORDER BY ". $order
			;
			A25_DI::DB()->setQuery( $query );
			$options = A25_DI::DB()->loadObjectList();
			return $options;
		} else {
			return array();
		}
	}

	/**
	 * A25_Record_User::store() uses some annoying global variables that get in the way
	 * when I am trying to create users simply for setting up tests.  Using
	 * this function instead, it skips the global variables and their actions.
	 * It also throws exceptions on DB errors, unlike A25_Record_User::store().
	 */
	public function storeWithoutUpdatingAcl($updateNulls = false)
	{
		$k = $this->getPrimaryKeyFieldName();
		$key =  $this->$k;
		if( $key ) {
			// existing record
			$ret = A25_DI::DB()->updateObject( $this->getTableName(), $this, $this->getPrimaryKeyFieldName(), $updateNulls );
			if(A25_DI::DB()->_errorMsg)
				throw new Exception(A25_DI::DB()->_errorMsg);
		} else {
			// new record
			$ret = A25_DI::DB()->insertObject( $this->getTableName(), $this, $this->getPrimaryKeyFieldName() );
			if(A25_DI::DB()->_errorMsg)
				throw new Exception(A25_DI::DB()->_errorMsg);
		}
		if( !$ret ) {
			$this->_error = strtolower(get_class( $this ))."::store failed <br />" . A25_DI::DB()->getErrorMsg();
			return false;
		} else {
			return true;
		}
	}
	public function courts()
	{
		if ($this->_courtsAreNotLoaded)
			$this->_loadCourts();
		return $this->_courts;
	}
	public function courtIds()
	{
		if ($this->_courtsAreNotLoaded)
			$this->_loadCourts();
		$courtIds = array();
		if ($this->_courts) {
			foreach ($this->_courts as $court)
			{
				$courtIds[] = $court->court_id;
			}
		}
		return $courtIds;
	}
	
	protected function _loadCourts()
	{
		if ($this->isAdminOrHigher())
		{
			$this->_courts = Doctrine_Query::create()
				->from('A25_Record_Court c')
				->execute();
		}
		if ($this->isCourtAdministrator())
		{
			$this->_courts = Doctrine_Query::create()
				->from('A25_Record_Court c')
				->innerJoin('c.UserXref x')
				->where('x.user_id = ?', $this->id)
				->execute();
		}
		$this->_courtsAreNotLoaded = false;
	}
	public function getMultipleFee()
	{
		return number_format($this->multiple_fee);
	}
	public function sendMessage($subject, $body)
	{
		$message = new JosMessages();
		$message->user_id_from = PlatformConfig::messageSenderId;
		$message->user_id_to = $this->id;
		$message->subject = $subject;
		$message->message = $body;
		$message->date_time = A25_Functions::formattedDateTime();
		$message->save();
		A25_DI::Mailer()->mail($this->email,$subject,$body,0);
	}

	public function getLocations()
	{
		$locations = array();

		foreach ($this->UserLocations as $ul) {
			$locations[] = $ul->Location;
		}

		return $locations;
	}
}
?>
