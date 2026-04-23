<?php

class A25_Record_IndependentDonation extends IndependentDonation
{
  const reason_None = 1;
  const reason_LicensePlate = 2;
  const reason_CourtOrder = 3;

    public function __construct($table = null, $isNewEntry = false) {
		parent::__construct($table, $isNewEntry);

        // Handle x_amount so that authorize.net-compatible forms can be bound
        // to this object without any errors:
        $this->hasMutator('x_amount', 'setAmount');
    }


  public static function retrieve( $id)
  {
    return Doctrine::getTable('A25_Record_IndependentDonation')->find($id);
  }

	public function courtName()
	{
		if ($this->relatedIsDefined('Court'))
			return $this->Court->court_name;
	}

    public function setAmount($value, $load = true)
    {
        if($value)
            $this->amount = $value;
    }

  public function save()
  {
    if (!$this->id) {
      $this->id = strtr(substr($this->md5_base64(microtime()), 0, 22), '+/', '-_');
    }
    parent::save();
  }

  private function md5_base64($data)
  {
    return base64_encode(pack('H*',md5($data)));
  }
}
