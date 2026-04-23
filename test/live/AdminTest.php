<?php

class test_live_AdminTest extends
		test_Framework_SeleniumTestCase
{
  public function setUp()
  {
    parent::setUp();
    A25_DoctrineRecord::$disableSave = true;
  }
  protected function _loginAsSuperAdministrator()
  {
    $this->openRelative('/administrator/index.php');
    $this->type('usrname', 'superadmin');
    $this->type('pass', 'AliveAt25');
    $this->clickAndWait('submit');
  }
  function testABunchOfAreas()
  {
    $this->_loginAsSuperAdministrator();
    $this->openRelative('/administrator/list-courses');
    $this->assertTextPresent('Found');
    $this->openRelative('/administrator/index2.php?option=com_court&task=list');
    $this->assertTextPresent('Display #');
    $this->openRelative('/administrator/index2.php?option=com_location&task=A25Config');
    $this->assertTextPresent('Payment Instructions:');
    $this->openRelative('/administrator/index2.php?option=com_location&task=list');
    $this->assertTextPresent('Results');
  }
  /**
   * This is not a special group compared to the first test.  The tests were
   * just taking too long.
   */
  function testASecondGroupOfAreas()
  {
    $this->_loginAsSuperAdministrator();
    $this->openRelative('/administrator/index2.php?option=com_pay&task=listcredittypes');
    $this->assertTextPresent('Display #');

    // @todo: this sometimes fails on aliveat25.us, because it runs a large
    // query.  We should fix this by limiting the start and end dates.
    $this->openRelative('/administrator/index2.php?option=com_pay&task=list');
    $this->assertTextPresent('Display #');
  }
}
