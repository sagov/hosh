<?php

class Hosh_List_Element  extends Hosh_List_Abstract
{



    /**
     * Element filters
     * @var array
     */
    protected $_filters = array();


    /**
     * Array to which element belongs
     * @var string
     */
    protected $_belongsTo;


    /**
     * Does the element represent an array?
     * @var bool
     */
    protected $_isArray = false;



    /**
     * Element type
     * @var string
     */
    protected $_type;

    /**
     * Default view helper to use
     * @var string
     */
    public $helper = 'formText';

    /**
     * Element name
     * @var string
     */
    protected $_name;

    /**
     * Order of element
     * @var int
     */
    protected $_order;



    /**
     * Element value
     *
     * @var mixed
     */
    protected $_value;



    /**
     * Is a specific decorator being rendered via the magic renderDecorator()?
     *
     * This is to allow execution of logic inside the render() methods of child
     * elements during the magic call while skipping the parent render() method.
     *
     * @var bool
     */
    protected $_isPartialRendering = false;

    /**
     * Should we disable loading the default decorators?
     * @var bool
     */
    protected $_disableLoadDefaultDecorators = false;


    protected $_prefName;

    /**
     * Constructor
     *
     * $spec may be:
     * - string: name of element
     * - array: options with which to configure element
     * - Zend_Config: Zend_Config with options for configuring element
     *
     * @param  string|array|Zend_Config $spec
     * @param  array|Zend_Config $options
     * @return void
     * @throws Zend_Form_Exception if no element name after initialization
     */
    public function __construct($spec, $options = null)
    {
        if (is_string($spec)) {
            $this->setName($spec);
        } elseif (is_array($spec)) {
            $this->setOptions($spec);
        } elseif ($spec instanceof Zend_Config) {
            $this->setConfig($spec);
        }

        if (is_string($spec) && is_array($options)) {
            $this->setOptions($options);
        } elseif (is_string($spec) && ($options instanceof Zend_Config)) {
            $this->setConfig($options);
        }

        if (null === $this->getName()) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Zend_Form_Element requires each element to have a name');
        }

        /**
         * Extensions
         */
        $this->init();

        /**
         * Register ViewHelper decorator by default
         */
        $this->loadDefaultDecorators();
    }

    /**
     * Initialize object; used by extending classes
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Set flag to disable loading default decorators
     *
     * @param  bool $flag
     * @return Zend_Form_Element
     */
    public function setDisableLoadDefaultDecorators($flag)
    {
        $this->_disableLoadDefaultDecorators = (bool) $flag;
        return $this;
    }

    /**
     * Should we load the default decorators?
     *
     * @return bool
     */
    public function loadDefaultDecoratorsIsDisabled()
    {
        return $this->_disableLoadDefaultDecorators;
    }

    /**
     * Load default decorators
     *
     * @return Zend_Form_Element
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('ViewHelper');
        }
        return $this;
    }

    /**
     * Set object state from options array
     *
     * @param  array $options
     * @return Zend_Form_Element
     */
    public function setOptions(array $options)
    {
        if (isset($options['prefixPath'])) {
            $this->addPrefixPaths($options['prefixPath']);
            unset($options['prefixPath']);
        }

        if (isset($options['disableTranslator'])) {
            $this->setDisableTranslator($options['disableTranslator']);
            unset($options['disableTranslator']);
        }

        unset($options['options']);
        unset($options['config']);

        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (in_array($method, array('setTranslator', 'setPluginLoader', 'setView'))) {
                if (!is_object($value)) {
                    continue;
                }
            }

            if (method_exists($this, $method)) {
                // Setter exists; use it
                $this->$method($value);
            } else {
                // Assume it's metadata
                $this->setAttrib($key, $value);
            }
        }
        return $this;
    }

    /**
     * Set object state from Zend_Config object
     *
     * @param  Zend_Config $config
     * @return Zend_Form_Element
     */
    public function setConfig(Zend_Config $config)
    {
        return $this->setOptions($config->toArray());
    }

    // Metadata

    /**
     * Filter a name to only allow valid variable characters
     *
     * @param  string $value
     * @param  bool $allowBrackets
     * @return string
     */
    public function filterName($value, $allowBrackets = false)
    {
        $charset = '^a-zA-Z0-9_\x7f-\xff';
        if ($allowBrackets) {
            $charset .= '\[\]';
        }

        return preg_replace('/[' . $charset . ']/', '', (string) $value);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPrefName($value)
    {
        $this->_prefName = $this->filterName($value);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrefName()
    {
        return $this->_prefName;
    }

    /**
     * Set element name
     *
     * @param  string $name
     * @return Hosh_List_Element
     */
    public function setName($name)
    {
        $name = $this->filterName($name);
        if ('' === $name) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Invalid name provided; must contain only valid variable characters and be non-empty');
        }

        $this->_name = $name;
        return $this;
    }

    /**
     * Return element name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
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
        $name = $this->getName();
        $pref = $this->getPrefName();
        if (isset($pref)){
            $name = $pref.$name;
        }
        return $name;
    }

    /**
     * Get element id
     *
     * @return string
     */
    public function getId()
    {
        if (isset($this->id)) {
            return $this->id;
        }

        $id = $this->getFullyQualifiedName();

        // Bail early if no array notation detected
        if (!strstr($id, '[')) {
            return $id;
        }

        // Strip array notation
        if ('[]' == substr($id, -2)) {
            $id = substr($id, 0, strlen($id) - 2);
        }
        $id = str_replace('][', '-', $id);
        $id = str_replace(array(']', '['), '-', $id);
        $id = trim($id, '-');

        return $id;
    }

    /**
     * Set element value
     *
     * @param  mixed $value
     * @return Hosh_List_Element
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * Retrieve filtered element value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Retrieve unfiltered element value
     *
     * @return mixed
     */
    public function getUnfilteredValue()
    {
        return $this->_value;
    }

    /**
     * Return element type
     *
     * @return string
     */
    public function getType()
    {
        if (null === $this->_type) {
            $this->_type = get_class($this);
        }

        return $this->_type;
    }

    /**
     * Set flag indicating if element represents an array
     *
     * @param  bool $flag
     * @return Zend_Form_Element
     */
    public function setIsArray($flag)
    {
        $this->_isArray = (bool) $flag;
        return $this;
    }

    /**
     * Is the element representing an array?
     *
     * @return bool
     */
    public function isArray()
    {
        return $this->_isArray;
    }

    /**
     * Set array to which element belongs
     *
     * @param  string $array
     * @return Zend_Form_Element
     */
    public function setBelongsTo($array)
    {
        $array = $this->filterName($array, true);
        if (!empty($array)) {
            $this->_belongsTo = $array;
        }

        return $this;
    }

    /**
     * Return array name to which element belongs
     *
     * @return string
     */
    public function getBelongsTo()
    {
        return $this->_belongsTo;
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
        $name = (string) $name;
        if ('_' == $name[0]) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(sprintf('Invalid attribute "%s"; must not contain a leading underscore', $name));
        }

        if (null === $value) {
            unset($this->$name);
        } else {
            $this->$name = $value;
        }

        return $this;
    }

    /**
     * Set multiple attributes at once
     *
     * @param  array $attribs
     * @return Zend_Form_Element
     */
    public function setAttribs(array $attribs)
    {
        foreach ($attribs as $key => $value) {
            $this->setAttrib($key, $value);
        }

        return $this;
    }

    /**
     * Retrieve element attribute
     *
     * @param  string $name
     * @return string
     */
    public function getAttrib($name)
    {
        $name = (string) $name;
        if (isset($this->$name)) {
            return $this->$name;
        }

        return null;
    }

    /**
     * Return all attributes
     *
     * @return array
     */
    public function getAttribs()
    {
        $attribs = get_object_vars($this);
        unset($attribs['helper']);
        foreach ($attribs as $key => $value) {
            if ('_' == substr($key, 0, 1)) {
                unset($attribs[$key]);
            }
        }

        return $attribs;
    }


}