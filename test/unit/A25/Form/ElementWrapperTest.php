<?php
require_once(dirname(__FILE__) . '/../../../../autoload.php');

class test_unit_A25_Form_ElementWrapperTest extends
		test_Framework_UnitTestCase
{
	private $_paramId = 0;

	/**
	 * This is a unit test.
	 * 
	 * It tests that every single public function of Zend_Form_Element is
	 * correctly wrapped by A25_Form_ElementWrapper.
	 * 
	 * @test
	 */
	public function shouldWrapEveryFunction()
	{
		$element = $this->mock('Zend_Form_Element');
		$elementWrapper = $this->getMock('A25_Form_ElementWrapper',
			array('_callInElement'), array($element));

		$futureCalls = $this->createFutureMethodCallsArray(
				$this->getWrappableMethods('Zend_Form_Element'));
		$this->addExpectationsForFutureMethodCalls($elementWrapper, $futureCalls);
		$this->executeMethodCalls($elementWrapper, $futureCalls);
	}
	/**
	 * Returns all public methods except for __construct and __call.
	 * 
	 * @param <type> $className
	 * @return <type>
	 */
	private function getWrappableMethods($className)
	{
		$class = new ReflectionClass($className);
		$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

		$methods = $this->removeItem($methods, '__construct');
		$methods = $this->removeItem($methods, '__call');

		return $methods;
	}
	private function removeItem(array $collection, $nameOfMethod)
	{
		$methods = array();
		foreach ($collection as $method) {
			if ($method->getName() != $nameOfMethod) {
				array_push($methods, $method);
			}
		}
		return $methods;
	}
	private function createFutureMethodCallsArray($methods)
	{
		$futureCalls = array();
		foreach ($methods as $method) {
			$parameters = $this->createParameters($method);
			array_push($futureCalls, array('method' => $method->getName(),
					'params' => $parameters));
		}
		return $futureCalls;
	}
	private function addExpectationsForFutureMethodCalls($wrapper, array $futureCalls)
	{
		$i = 0;
		foreach ($futureCalls as $call) {
			$wrapper->expects($this->at($i))
					->method('_callInElement')
					->with($call['method'],$call['params'])
					->will($this->returnValue(null));
			$i++;
		}
	}
	private function executeMethodCalls($elementWrapper, $futureCalls)
	{
		foreach ($futureCalls as $call) {
			call_user_func_array(array($elementWrapper, $call['method']), $call['params']);
		}
	}
	private function createParameters(ReflectionMethod $method) {
		$reflectedParameters = $method->getParameters();
		$parameters = array();
		foreach ($reflectedParameters as $reflectedParameter) {
			$parameter = $this->createParameter($reflectedParameter);
			array_push($parameters,$parameter);
		}
		return $parameters;
	}
	private function createParameter(ReflectionParameter $reflectedParameter)
	{
		if ($reflectedParameter->isArray()) {
			$parameter = array($this->uniqueParameterValue());
		} else if ($reflectedParameter->getClass()) {
			$className = $reflectedParameter->getClass()->getName();
			$parameter = $this->mock($className);
		}
		else {
			$parameter = $this->uniqueParameterValue();
		}
		return $parameter;
	}
	private function uniqueParameterValue()
	{
		$return = "Parameter #$this->_paramId";
		$this->_paramId++;
		return $return;
	}
}
?>
