<?php
class A25_Record_LocationParent extends A25_Record_LocationAbstract
{
	public function construct() {
		if (!$this->exists())
			$this->is_location = 0;
	}

	/**
	 * @param integer $id
	 * @return A25_Record_LocationParent
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_LocationParent')->find($id);
    }

	public static function retrieveAllAvailable()
	{
		$parentLocation = self::retrieve(ServerConfig::parentLocationId);
		return array($parentLocation);
	}
	
	function showLocationInfo( $mode = 'full' ) {
		return $this->description;
	}

	public function settingParent()
	{
		return null;
	}
}
