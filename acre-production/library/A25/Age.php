<?php
class A25_Age
{
	private $_years;
	private $_days;
	function __construct($years,$days=0)
	{
		$this->_years = $years;
		$this->_days = $days;
	}
	public function formattedBirthday()
	{
		return date('m/d/Y', $this->birthdayTimestamp());
	}
	public function birthdayTimestamp()
	{
		return strtotime("-$this->_years years -$this->_days days");
	}
}
?>