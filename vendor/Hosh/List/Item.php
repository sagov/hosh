<?php

class Hosh_List_Item extends Hosh_List_Abstract
{

    protected $_elements = array();

    protected $_elementDecorators;

    protected $_elementPrefixPaths;

    protected $_name;

    public function __construct($name = null)
    {
        if (isset($name)){
            $this->setName($name);
        }
    }

    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }


    /**
     * Add a new element
     *
     * $element may be either a string element type, or an object of type
     * Zend_Form_Element. If a string element type is provided, $name must be
     * provided, and $options may be optionally provided for configuring the
     * element.
     *
     * If a Zend_Form_Element is provided, $name may be optionally provided,
     * and any provided $options will be ignored.
     *
     * @param  string|Zend_Form_Element $element
     * @param  string $name
     * @param  array|Zend_Config $options
     * @throws Zend_Form_Exception on invalid element
     * @return Zend_Form
     */
    public function addElement($element, $name = null, $options = null)
    {
        if (is_string($element)) {
            if (null === $name) {
                require_once 'Zend/Form/Exception.php';
                throw new Zend_Form_Exception(
                    'Elements specified by string must have an accompanying name'
                );
            }

            $this->_elements[$name] = $this->createElement($element, $name, $options);
        } elseif ($element instanceof Hosh_List_Element) {
            $prefixPaths              = array();
            $prefixPaths['decorator'] = $this->getPluginLoader('decorator')->getPaths();
            if (!empty($this->_elementPrefixPaths)) {
                $prefixPaths = array_merge($prefixPaths, $this->_elementPrefixPaths);
            }

            if (is_array($this->_elementDecorators)
                && 0 == count($element->getDecorators())
            ) {
                $element->setDecorators($this->_elementDecorators);
            }

            if (null === $name) {
                $name = $element->getName();
            }

            $this->_elements[$name] = $element;

        } else {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(
                'Element must be specified by string or Zend_Form_Element instance'
            );
        }


        return $this;
    }

    /**
     * Create an element
     *
     * Acts as a factory for creating elements. Elements created with this
     * method will not be attached to the form, but will contain element
     * settings as specified in the form object (including plugin loader
     * prefix paths, default decorators, etc.).
     *
     * @param  string $type
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Hosh_List_Element
     */
    public function createElement($type, $name, $options = null)
    {
        if (!is_string($type)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Element type must be a string indicating type');
        }

        if (!is_string($name)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Element name must be a string');
        }

        $prefixPaths              = array();
        $prefixPaths['decorator'] = $this->getPluginLoader('decorator')->getPaths();
        if (!empty($this->_elementPrefixPaths)) {
            $prefixPaths = array_merge($prefixPaths, $this->_elementPrefixPaths);
        }

        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if ((null === $options) || !is_array($options)) {
            $options = array('prefixPath' => $prefixPaths);

            if (is_array($this->_elementDecorators)) {
                $options['decorators'] = $this->_elementDecorators;
            }
        } elseif (is_array($options)) {
            if (array_key_exists('prefixPath', $options)) {
                $options['prefixPath'] = array_merge($prefixPaths, $options['prefixPath']);
            } else {
                $options['prefixPath'] = $prefixPaths;
            }

            if (is_array($this->_elementDecorators)
                && !array_key_exists('decorators', $options)
            ) {
                $options['decorators'] = $this->_elementDecorators;
            }
        }

        $class = $this->getPluginLoader(self::ELEMENT)->load($type);
        $element = new $class($name, $options);

        return $element;
    }

    /**
     * Retrieve a single element
     *
     * @param  string $name
     * @return Hosh_List_Element|null
     */
    public function getElement($name)
    {
        if (array_key_exists($name, $this->_elements)) {
            return $this->_elements[$name];
        }
        return null;
    }

    /**
     * Retrieve all elements
     *
     * @return array
     */
    public function getElements()
    {
        return $this->_elements;
    }
}