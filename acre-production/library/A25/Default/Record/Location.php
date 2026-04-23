<?php

class A25_Default_Record_Location extends A25_Record_LocationAbstract
{
	public function setUp()
	{
		$this->hasOne('A25_Record_LocationParent as Parent', array(
				'local'=>'parent',
				'foreign'=>'location_id'
			)
		);
		return parent::setUp();
	}

	public function settingParent()
	{
		if ($this->relatedIsDefined('Parent'))
			return $this->Parent;
		 else
			return null;
	}
}
