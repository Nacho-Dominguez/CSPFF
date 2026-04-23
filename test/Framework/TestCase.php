<?php

abstract class test_Framework_TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * This property is used by PHPUnit's code generation, but was never declared.
	 */
	public $__liHtml;
	
	/**
	 * These __get and __set functions ensure that no non-existant properties
     * are actually used.  This protects against mis-typing.
     */
    public function __get($name)
    {
		A25_StrictObject::throwPropertyException($name, $this);
    }
    public function __set($name,$value)
    {
		A25_StrictObject::throwPropertyException($name, $this);
    }

	public function setUp()
	{
		global $acl;
		$acl = null;
		$conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    $conn->evictTables();
		A25_DI::reset();
	}
	/**
     * Constructs a test case with the given name.
     *
     * @param  string $name
     * @param  array  $data
     * @param  string $dataName
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
		parent::__construct($name, $data, $dataName);
    }

	public function assertEqualsIgnoringWhitespace($expected, $actual,
			$message='')
	{
		$expected = test_HelperFunctions::stripAllWhitespace($expected);
		$actual = test_HelperFunctions::stripAllWhitespace($actual);
		return $this->assertEquals($expected, $actual, $message);
	}

	/**
	 * This is the preferred way to mock an object.  It creates a 'Dynamic' mock
	 * with all functions mocked, including the constructor.
	 *
	 * @param string $className
	 * @return object
	 */
	protected function mock($className) {
		return $this->getMock($className, array(), array(), '', false);
	}

	protected function instantiateWithoutConstructor($className)
	{
		return $this->getMock($className,array('mockNothing'),array(),'',false);
	}
	
	protected function matchPatternInFile($pattern, $filepath)
	{
		$fileContents = file_get_contents($filepath,1);
		$matches = array();
		$didMatch = preg_match(
				$pattern,
				$fileContents,
				$matches);
		if (!$didMatch)
			throw new Exception('Pattern not matched in file.');
		return $matches[0];
	}
}
?>
