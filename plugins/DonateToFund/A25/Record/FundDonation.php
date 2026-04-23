<?php

class A25_Record_FundDonation extends A25_DoctrineRecord
{
    public function __construct($table = null, $isNewEntry = false) {
		parent::__construct($table, $isNewEntry);

        // Handle x_amount so that authorize.net-compatible forms can be bound
        // to this object without any errors:
        $this->hasMutator('x_amount', 'setAmount');
    }
    public function setTableDefinition()
    {
        $this->setTableName('fund_donation');
        $this->hasColumn('id', 'string', 22, array(
             'type' => 'string',
             'length' => 22,
             'fixed' => true,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('amount', 'decimal', 6, array(
             'type' => 'decimal',
             'length' => 6,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'scale' => '2',
             ));
        $this->hasColumn('pay_type_id', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('benefactor', 'string', 70, array(
             'type' => 'string',
             'length' => 70,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('cc_trans_id', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('fund_id', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('created', 'timestamp', null, array(
             'type' => 'timestamp',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '0000-00-00 00:00:00',
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('created_by', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        $this->hasOne(
            'A25_Record_Fund as Fund',
            array(
                'local' => 'fund_id',
                'foreign' => 'fund_id'
            )
        );
    }

    public static function retrieve( $id)
    {
        return Doctrine::getTable('A25_Record_FundDonation')->find($id);
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
