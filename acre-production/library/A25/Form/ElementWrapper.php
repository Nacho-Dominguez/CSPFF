<?php

/**
 * This class provides full wrapping (a.k.a. Decoration) of Zend_Form_Element.
 * It is not intended to be used by itself, but to be extended so that child
 * classes do not have to cover all 80+ functions of Zend_Form_Element.
 */
abstract class A25_Form_ElementWrapper extends Zend_Form_Element {
	protected $_element;
	public function __construct(Zend_Form_Element $element)
	{
		$this->_element = $element;
	}
	public function __call($name, $arguments) {
		return $this->_callInElement($name, $arguments);
    }
	

	/**
	 *
	 * @param <type> $name
	 * @param array $arguments
	 * @return object
	 */
	protected function _callInElement($methodName, array $arguments)
	{
		$return = call_user_func_array(array($this->_element, $methodName), $arguments);
		if ($return === $this->_element)
			return $this;
		else
			return $return;
	}

	private function run()
	{
		$trace = debug_backtrace();
		$caller = $trace[1];
		$name = $caller['function'];
		$arguments = $caller['args'];

		return $this->_callInElement($name, $arguments);
	}

    /**
     * Initialize object; used by extending classes
     *
     * @return void
     */
    public function init()
    {
		return $this->run();
    }

    /**
     * Set flag to disable loading default decorators
     *
     * @param  bool $flag
     * @return Zend_Form_Element
     */
    public function setDisableLoadDefaultDecorators($flag)
    {
		return $this->run();
    }

    /**
     * Should we load the default decorators?
     *
     * @return bool
     */
    public function loadDefaultDecoratorsIsDisabled()
    {
		return $this->run();
    }

    /**
     * Load default decorators
     *
     * @return void
     */
    public function loadDefaultDecorators()
    {
		return $this->run();
    }

    /**
     * Set object state from options array
     *
     * @param  array $options
     * @return Zend_Form_Element
     */
    public function setOptions(array $options)
    {
		return $this->run();
    }

	/**
     * Set object state from Zend_Config object
     *
     * @param  Zend_Config $config
     * @return Zend_Form_Element
     */
    public function setConfig(Zend_Config $config)
    {
		return $this->run();
    }

    // Localization:

    /**
     * Set translator object for localization
     *
     * @param  Zend_Translate|null $translator
     * @return Zend_Form_Element
     */
    public function setTranslator($translator = null)
    {
		return $this->run();
    }

    /**
     * Retrieve localization translator object
     *
     * @return Zend_Translate_Adapter|null
     */
    public function getTranslator()
    {
		return $this->run();
    }

    /**
     * Indicate whether or not translation should be disabled
     *
     * @param  bool $flag
     * @return Zend_Form_Element
     */
    public function setDisableTranslator($flag)
    {
		return $this->run();
    }

    /**
     * Is translation disabled?
     *
     * @return bool
     */
    public function translatorIsDisabled()
    {
		return $this->run();
    }
	/**
     * Filter a name to only allow valid variable characters
     *
     * @param  string $value
     * @param  bool $allowBrackets
     * @return string
     */
    public function filterName($value, $allowBrackets = false)
    {
		return $this->run();
    }

    /**
     * Set element name
     *
     * @param  string $name
     * @return Zend_Form_Element
     */
    public function setName($name)
    {
		return $this->run();
    }

    /**
     * Return element name
     *
     * @return string
     */
    public function getName()
    {
		return $this->run();
    }

    /**
     * Get fully qualified name
     *
     * Places name as subitem of array and/or appends brackets.
     *
     * @return string
     */
    public function getFullyQualifiedName()
    {
		return $this->run();
    }

    /**
     * Get element id
     *
     * @return string
     */
    public function getId()
    {
		return $this->run();
    }

    /**
     * Set element value
     *
     * @param  mixed $value
     * @return Zend_Form_Element
     */
    public function setValue($value)
    {
		return $this->run();
    }

    /**
     * Filter a value
     *
     * @param  string $value
     * @param  string $key
     * @return void
     */
    protected function _filterValue(&$value, &$key)
    {
		return $this->run();
    }

    /**
     * Retrieve filtered element value
     *
     * @return mixed
     */
    public function getValue()
    {
		return $this->run();
    }

    /**
     * Retrieve unfiltered element value
     *
     * @return mixed
     */
    public function getUnfilteredValue()
    {
		return $this->run();
    }

    /**
     * Set element label
     *
     * @param  string $label
     * @return Zend_Form_Element
     */
    public function setLabel($label)
    {
		return $this->run();
    }

    /**
     * Retrieve element label
     *
     * @return string
     */
    public function getLabel()
    {
		return $this->run();
    }

    /**
     * Set element order
     *
     * @param  int $order
     * @return Zend_Form_Element
     */
    public function setOrder($order)
    {
		return $this->run();
    }

    /**
     * Retrieve element order
     *
     * @return int
     */
    public function getOrder()
    {
		return $this->run();
    }

    /**
     * Set required flag
     *
     * @param  bool $flag Default value is true
     * @return Zend_Form_Element
     */
    public function setRequired($flag = true)
    {
		return $this->run();
    }

    /**
     * Is the element required?
     *
     * @return bool
     */
    public function isRequired()
    {
		return $this->run();
    }

    /**
     * Set flag indicating whether a NotEmpty validator should be inserted when element is required
     *
     * @param  bool $flag
     * @return Zend_Form_Element
     */
    public function setAutoInsertNotEmptyValidator($flag)
    {
		return $this->run();
    }

    /**
     * Get flag indicating whether a NotEmpty validator should be inserted when element is required
     *
     * @return bool
     */
    public function autoInsertNotEmptyValidator()
    {
		return $this->run();
    }

    /**
     * Set element description
     *
     * @param  string $description
     * @return Zend_Form_Element
     */
    public function setDescription($description)
    {
		return $this->run();
    }

    /**
     * Retrieve element description
     *
     * @return string
     */
    public function getDescription()
    {
		return $this->run();
    }

    /**
     * Set 'allow empty' flag
     *
     * When the allow empty flag is enabled and the required flag is false, the
     * element will validate with empty values.
     *
     * @param  bool $flag
     * @return Zend_Form_Element
     */
    public function setAllowEmpty($flag)
    {
		return $this->run();
    }

    /**
     * Get 'allow empty' flag
     *
     * @return bool
     */
    public function getAllowEmpty()
    {
		return $this->run();
    }

    /**
     * Set ignore flag (used when retrieving values at form level)
     *
     * @param  bool $flag
     * @return Zend_Form_Element
     */
    public function setIgnore($flag)
    {
		return $this->run();
    }

    /**
     * Get ignore flag (used when retrieving values at form level)
     *
     * @return bool
     */
    public function getIgnore()
    {
		return $this->run();
    }

    /**
     * Set flag indicating if element represents an array
     *
     * @param  bool $flag
     * @return Zend_Form_Element
     */
    public function setIsArray($flag)
    {
		return $this->run();
    }

    /**
     * Is the element representing an array?
     *
     * @return bool
     */
    public function isArray()
    {
		return $this->run();
    }

    /**
     * Set array to which element belongs
     *
     * @param  string $array
     * @return Zend_Form_Element
     */
    public function setBelongsTo($array)
    {
		return $this->run();
    }

    /**
     * Return array name to which element belongs
     *
     * @return string
     */
    public function getBelongsTo()
    {
		return $this->run();
    }

    /**
     * Return element type
     *
     * @return string
     */
    public function getType()
    {
		return $this->run();
    }

    /**
     * Set element attribute
     *
     * @param  string $name
     * @param  mixed $value
     * @return Zend_Form_Element
     * @throws Zend_Form_Exception for invalid $name values
     */
    public function setAttrib($name, $value)
    {
		return $this->run();
    }

    /**
     * Set multiple attributes at once
     *
     * @param  array $attribs
     * @return Zend_Form_Element
     */
    public function setAttribs(array $attribs)
    {
		return $this->run();
    }

    /**
     * Retrieve element attribute
     *
     * @param  string $name
     * @return string
     */
    public function getAttrib($name)
    {
		return $this->run();
    }

    /**
     * Return all attributes
     *
     * @return array
     */
    public function getAttribs()
    {
		return $this->run();
    }

    /**
     * Overloading: retrieve object property
     *
     * Prevents access to properties beginning with '_'.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
		return $this->run();
    }

    /**
     * Overloading: set object property
     *
     * @param  string $key
     * @param  mixed $value
     * @return voide
     */
    public function __set($key, $value)
    {
		return $this->run();
    }

    
    // Loaders

    /**
     * Set plugin loader to use for validator or filter chain
     *
     * @param  Zend_Loader_PluginLoader_Interface $loader
     * @param  string $type 'decorator', 'filter', or 'validate'
     * @return Zend_Form_Element
     * @throws Zend_Form_Exception on invalid type
     */
    public function setPluginLoader(Zend_Loader_PluginLoader_Interface $loader, $type)
    {
		return $this->run();
    }

    /**
     * Retrieve plugin loader for validator or filter chain
     *
     * Instantiates with default rules if none available for that type. Use
     * 'decorator', 'filter', or 'validate' for $type.
     *
     * @param  string $type
     * @return Zend_Loader_PluginLoader
     * @throws Zend_Loader_Exception on invalid type.
     */
    public function getPluginLoader($type)
    {
		return $this->run();
    }

    /**
     * Add prefix path for plugin loader
     *
     * If no $type specified, assumes it is a base path for both filters and
     * validators, and sets each according to the following rules:
     * - decorators: $prefix = $prefix . '_Decorator'
     * - filters: $prefix = $prefix . '_Filter'
     * - validators: $prefix = $prefix . '_Validate'
     *
     * Otherwise, the path prefix is set on the appropriate plugin loader.
     *
     * @param  string $path
     * @return Zend_Form_Element
     * @throws Zend_Form_Exception for invalid type
     */
    public function addPrefixPath($prefix, $path, $type = null)
    {
		return $this->run();
    }

    /**
     * Add many prefix paths at once
     *
     * @param  array $spec
     * @return Zend_Form_Element
     */
    public function addPrefixPaths(array $spec)
    {
		return $this->run();
    }

    // Validation

    /**
     * Add validator to validation chain
     *
     * Note: will overwrite existing validators if they are of the same class.
     *
     * @param  string|Zend_Validate_Interface $validator
     * @param  bool $breakChainOnFailure
     * @param  array $options
     * @return Zend_Form_Element
     * @throws Zend_Form_Exception if invalid validator type
     */
    public function addValidator($validator, $breakChainOnFailure = false, $options = array())
    {
		return $this->run();
    }

    /**
     * Add multiple validators
     *
     * @param  array $validators
     * @return Zend_Form_Element
     */
    public function addValidators(array $validators)
    {
		return $this->run();
    }

    /**
     * Set multiple validators, overwriting previous validators
     *
     * @param  array $validators
     * @return Zend_Form_Element
     */
    public function setValidators(array $validators)
    {
		return $this->run();
    }

    /**
     * Retrieve a single validator by name
     *
     * @param  string $name
     * @return Zend_Validate_Interface|false False if not found, validator otherwise
     */
    public function getValidator($name)
    {
		return $this->run();
    }

    /**
     * Retrieve all validators
     *
     * @return array
     */
    public function getValidators()
    {
		return $this->run();
    }

    /**
	 * Remove a single validator by name
     *
     * @param  string $name
     * @return bool
     */
    public function removeValidator($name)
    {
		return $this->run();
    }

    /**
     * Clear all validators
     *
     * @return Zend_Form_Element
     */
    public function clearValidators()
    {
		return $this->run();
    }

    /**
     * Validate element value
     *
     * If a translation adapter is registered, any error messages will be
     * translated according to the current locale, using the given error code;
     * if no matching translation is found, the original message will be
     * utilized.
     *
     * Note: The *filtered* value is validated.
     *
     * @param  mixed $value
     * @param  mixed $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
		return $this->run();
    }

    /**
     * Add a custom error message to return in the event of failed validation
     *
     * @param  string $message
     * @return Zend_Form_Element
     */
    public function addErrorMessage($message)
    {
		return $this->run();
    }

    /**
     * Add multiple custom error messages to return in the event of failed validation
     *
     * @param  array $messages
     * @return Zend_Form_Element
     */
    public function addErrorMessages(array $messages)
    {
		return $this->run();
    }

    /**
     * Same as addErrorMessages(), but clears custom error message stack first
     *
     * @param  array $messages
     * @return Zend_Form_Element
     */
    public function setErrorMessages(array $messages)
    {
		return $this->run();
    }

    /**
     * Retrieve custom error messages
     *
     * @return array
     */
    public function getErrorMessages()
    {
		return $this->run();
    }

    /**
     * Clear custom error messages stack
     *
     * @return Zend_Form_Element
     */
    public function clearErrorMessages()
    {
		return $this->run();
    }

    /**
     * Mark the element as being in a failed validation state
     *
     * @return Zend_Form_Element
     */
    public function markAsError()
    {
		return $this->run();
    }

    /**
     * Add an error message and mark element as failed validation
     *
     * @param  string $message
     * @return Zend_Form_Element
     */
    public function addError($message)
    {
		return $this->run();
    }

    /**
     * Add multiple error messages and flag element as failed validation
     *
     * @param  array $messages
     * @return Zend_Form_Element
     */
    public function addErrors(array $messages)
    {
		return $this->run();
    }

    /**
     * Overwrite any previously set error messages and flag as failed validation
     *
     * @param  array $messages
     * @return Zend_Form_Element
     */
    public function setErrors(array $messages)
    {
		return $this->run();
    }

    /**
     * Are there errors registered?
     *
     * @return bool
     */
    public function hasErrors()
    {
		return $this->run();
    }

    /**
     * Retrieve validator chain errors
     *
     * @return array
     */
    public function getErrors()
    {
		return $this->run();
    }

    /**
     * Retrieve error messages
     *
     * @return array
     */
    public function getMessages()
    {
		return $this->run();
    }


    // Filtering

    /**
     * Add a filter to the element
     *
     * @param  string|Zend_Filter_Interface $filter
     * @return Zend_Form_Element
     */
    public function addFilter($filter, $options = array())
    {
		return $this->run();
    }

    /**
     * Add filters to element
     *
     * @param  array $filters
     * @return Zend_Form_Element
     */
    public function addFilters(array $filters)
    {
		return $this->run();

        return $this;
    }

    /**
     * Add filters to element, overwriting any already existing
     *
     * @param  array $filters
     * @return Zend_Form_Element
     */
    public function setFilters(array $filters)
    {
		return $this->run();
    }

    /**
     * Retrieve a single filter by name
     *
     * @param  string $name
     * @return Zend_Filter_Interface
     */
    public function getFilter($name)
    {
		return $this->run();
    }

    /**
     * Get all filters
     *
     * @return array
     */
    public function getFilters()
    {
		return $this->run();
    }

    /**
     * Remove a filter by name
     *
     * @param  string $name
     * @return Zend_Form_Element
     */
    public function removeFilter($name)
    {
		return $this->run();
    }

    /**
     * Clear all filters
     *
     * @return Zend_Form_Element
     */
    public function clearFilters()
    {
		return $this->run();
    }

    // Rendering

    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_Form_Element
     */
    public function setView(Zend_View_Interface $view = null)
    {
		return $this->run();
    }

    /**
     * Retrieve view object
     *
     * Retrieves from ViewRenderer if none previously set.
     *
     * @return null|Zend_View_Interface
     */
    public function getView()
    {
		return $this->run();
    }

    /**
     * Instantiate a decorator based on class name or class name fragment
     *
     * @param  string $name
     * @param  null|array $options
     * @return Zend_Form_Decorator_Interface
     */
    protected function _getDecorator($name, $options)
    {
		return $this->run();
    }

    /**
     * Add a decorator for rendering the element
     *
     * @param  string|Zend_Form_Decorator_Interface $decorator
     * @param  array|Zend_Config $options Options with which to initialize decorator
     * @return Zend_Form_Element
     */
    public function addDecorator($decorator, $options = null)
    {
		return $this->run();
    }

    /**
     * Add many decorators at once
     *
     * @param  array $decorators
     * @return Zend_Form_Element
     */
    public function addDecorators(array $decorators)
    {
		return $this->run();
    }

    /**
     * Overwrite all decorators
     *
     * @param  array $decorators
     * @return Zend_Form_Element
     */
    public function setDecorators(array $decorators)
    {
		return $this->run();
    }

    /**
     * Retrieve a registered decorator
     *
     * @param  string $name
     * @return false|Zend_Form_Decorator_Abstract
     */
    public function getDecorator($name)
    {
		return $this->run();
    }

    /**
     * Retrieve all decorators
     *
     * @return array
     */
    public function getDecorators()
    {
		return $this->run();
    }

    /**
     * Remove a single decorator
     *
     * @param  string $name
     * @return bool
     */
    public function removeDecorator($name)
    {
		return $this->run();
    }

    /**
     * Clear all decorators
     *
     * @return Zend_Form_Element
     */
    public function clearDecorators()
    {
		return $this->run();
    }

    /**
     * Render form element
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
		return $this->run();
    }

    /**
     * String representation of form element
     *
     * Proxies to {@link render()}.
     *
     * @return string
     */
    public function __toString()
    {
		return $this->run();
    }
}
?>
