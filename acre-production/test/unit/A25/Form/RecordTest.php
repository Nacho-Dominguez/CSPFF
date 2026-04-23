<?php
require_once(dirname(__FILE__) . '/../../../../autoload.php');

class test_unit_A25_Form_RecordTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
    public function setElementModes_doesntCallSetReadOnly_whenTypeIsNot_A25FormElement()
	{
		$element = $this->mock('Zend_Form_Element');
		$element->expects($this->never())->method('setReadOnly');

		$form = new A25_Form_Record_withMethodsExposed($this->mock('A25_Record'), null, true);
		$form->addElement($element);
		$form->setElementModes();
	}
	/**
	 * @test
	 */
    public function setElementModes_callsSetReadOnly_whenReadOnlyIsTrue_andTypeIs_A25FormElement()
	{
		$element = $this->mock('A25_Form_Element');
		$element->expects($this->once())->method('setReadOnly');

		$form = new A25_Form_Record_withMethodsExposed($this->mock('A25_Record'), null, true);
		$form->addElement($element);
		$form->setElementModes();
	}
	/**
	 * @test
	 */
    public function setElementModes_doesntCallSetReadOnly_whenReadOnlyIsFalse()
	{
		$element = $this->mock('A25_Form_Element');
		$element->expects($this->never())->method('setReadOnly');

		$form = new A25_Form_Record_withMethodsExposed($this->mock('A25_Record'), null, false);
		$form->addElement($element);
		$form->setElementModes();
	}

	/**
	 * @test
	 */
	public function populateAndSaveIfNecessary()
	{
		A25_DI::setDB($this->mock('A25_Db'));
		$coupon = new A25_Record_Coupon();
		$coupon->code = 'testCode';
		$form = new unit_A25_Form_Record($coupon);
		$form->populateAndSaveIfNecessary(null);

		$this->assertEquals($coupon->code, $form->getElement('code')->getValue());
	}

	/**
	 * @test
	 */
	public function setProperty_PropertyExists()
	{
		$value = 'fakeValue';
		$fieldName = 'code';

		$coupon = $this->setProperty($fieldName, $value);

		$this->assertEquals($value, $coupon->$fieldName);
	}
	private function setProperty($fieldName, $value)
	{
		A25_DI::setDB($this->mock('A25_Db'));
		$coupon = new A25_Record_Coupon();
		$element = new Zend_Form_Element_Text($fieldName);
		$element->setValue($value);
		$form = new unit_A25_Form_Record($coupon);
		$form->setProperty($element);

		return $coupon;
	}

	/**
	 * @test
	 */
	public function saveAndReturnMessage_catchesDataConstraintException()
	{
		$e = new A25_Exception_DataConstraint('Message');

		$message = $this->callSaveAndReturnMessage_andSaveFailsWithException($e);

		$this->assertEquals($e->getMessage(), $message);
	}

	/**
	 * @test
	 * @expectedException Exception
	 */
	public function saveAndReturnMessage_throwsNonDataConstraintException()
	{
		$e = new Exception('Message');

		$this->callSaveAndReturnMessage_andSaveFailsWithException($e);
	}

	private function callSaveAndReturnMessage_andSaveFailsWithException(
			Exception $e)
	{
		$coupon = $this->mock('A25_Record');
		$coupon->expects($this->any())->method('checkAndStore')->will($this->throwException($e));
		$form = new A25_Form_Record_withMethodsExposed(
				$coupon, null);

		return $form->saveAndReturnMessage($coupon);
	}

	/**
	 * @test
	 */
	public function validateAndSave_failValidation()
	{
		$db = $this->mock('A25_Db');
		$testData['code'] = 'TestCode';
		$testData['discount'] = '10.00';

		$coupon = new A25_Record_Coupon();
		$form = new unit_A25_Form_Record($coupon);

		$saveMessage = $form->validateAndSave($testData);

		$this->assertNull($saveMessage);
	}
}


class A25_Form_Record_withMethodsExposed extends A25_Form_Record
{
	public function setElementModes() {
		return parent::setElementModes();
	}
	public function populateAndSaveIfNecessary($data) {
		return parent::populateAndSaveIfNecessary($data);
	}
	public function saveAndReturnMessage() {
		return parent::saveAndReturnMessage();
	}
	public function setProperty(Zend_Form_Element $element)
	{
		return parent::setProperty($element);
	}
	public function validateAndSave($data) {
		return parent::validateAndSave($data);
	}
}

class unit_A25_Form_Record extends A25_Form_Record_withMethodsExposed {
	public function __construct(A25_Record_Coupon $coupon, $options = null)
    {
		$this->successMessage = 'Coupon Saved';

        $code = new Zend_Form_Element_Text('code');
        $code->setLabel('Coupon Code')
                  ->setRequired(true);

        $discount = new Zend_Form_Element_Text('discount');
        $discount->setLabel('Discount Amount')
                 ->setRequired(true);

        $numberLeft = new Zend_Form_Element_Text('numberLeft');
        $numberLeft->setLabel('Number Left')
                 ->setRequired(true);

		$this->addElements(array($code, $discount, $numberLeft));
        parent::__construct($coupon,'');

    }
}