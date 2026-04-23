<?php

namespace Acre\TestHelpers;

class AdminClicker
{
    private $selenium;

    public function __construct($selenium)
    {
        $this->selenium = $selenium;
    }

    public function loginAsSuperAdministrator()
    {
        $this->loginAs('superadmin', 'AliveAt25');
    }
    public function loginAs($username, $password)
    {
        $this->selenium->openRelative('/administrator/');
        $this->selenium->type('usrname', $username);
        $this->selenium->type('pass', $password);
        $this->selenium->clickAndWait('submit');
    }
    public function clickSaveButton()
    {
        $this->selenium->clickAndWait('task');
    }
}
