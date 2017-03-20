<?php

abstract class Hosh_List_Abstract
{

    const DECORATOR = 'DECORATOR';

    const ELEMENT = 'ELEMENT';

    const FILTER    = 'FILTER';


    /**
     * Plugin loaders
     * @var array
     */
    protected $_loaders = array();

    /**
     * Decorators for rendering
     * @var array
     */
    protected $_decorators = array();

    /**
     * @var Zend_View_Interface
     */
    protected $_view;

    /**
     * @var bool
     */
    protected $_isRendered = false;

    /**
     * @var Zend_Translate
     */
    protected $_translator;

    /**
     * Is translation disabled?
     * @var bool
     */
    protected $_translatorDisabled = false;


    // Rendering

    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return Hosh_List
     */
    public function setView(Zend_View_Interface $view = null)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * Retrieve view object
     *
     * If none registered, attempts to pull from ViewRenderer.
     *
     * @return Zend_View_Interface|null
     */
    public function getView()
    {
        if (null === $this->_view) {
            require_once 'Zend/Controller/Action/HelperBroker.php';
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $this->setView($viewRenderer->view);
        }

        return $this->_view;
    }

    /**
     * Instantiate a decorator based on class name or class name fragment
     *
     * @param  string $name
     * @param  null|array $options
     * @return Hosh_List_Decorator_Interface
     */
    protected function _getDecorator($name, $options)
    {
        $class = $this->getPluginLoader(self::DECORATOR)->load($name);
        if (null === $options) {
            $decorator = new $class;
        } else {
            $decorator = new $class($options);
        }

        return $decorator;
    }

    /**
     * Add a decorator for rendering the element
     *
     * @param  string|Hosh_List_Decorator_Interface $decorator
     * @param  array|Zend_Config $options Options with which to initialize decorator
     * @return Hosh_List
     */
    public function addDecorator($decorator, $options = null)
    {
        if ($decorator instanceof Hosh_List_Decorator_Interface) {
            $name = get_class($decorator);
        } elseif (is_string($decorator)) {
            $name      = $decorator;
            $decorator = array(
                'decorator' => $name,
                'options'   => $options,
            );
        } elseif (is_array($decorator)) {
            foreach ($decorator as $name => $spec) {
                break;
            }
            if (is_numeric($name)) {
                require_once 'Zend/Form/Exception.php';
                throw new Zend_Form_Exception('Invalid alias provided to addDecorator; must be alphanumeric string');
            }
            if (is_string($spec)) {
                $decorator = array(
                    'decorator' => $spec,
                    'options'   => $options,
                );
            } elseif ($spec instanceof Hosh_List_Decorator_Interface) {
                $decorator = $spec;
            }
        } else {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Invalid decorator provided to addDecorator; must be string or Zend_Form_Decorator_Interface');
        }

        $this->_decorators[$name] = $decorator;

        return $this;
    }

    /**
     * Add many decorators at once
     *
     * @param  array $decorators
     * @return Hosh_List
     */
    public function addDecorators(array $decorators)
    {
        foreach ($decorators as $decoratorName => $decoratorInfo) {
            if (is_string($decoratorInfo) ||
                $decoratorInfo instanceof Hosh_List_Decorator_Interface) {
                if (!is_numeric($decoratorName)) {
                    $this->addDecorator(array($decoratorName => $decoratorInfo));
                } else {
                    $this->addDecorator($decoratorInfo);
                }
            } elseif (is_array($decoratorInfo)) {
                $argc    = count($decoratorInfo);
                $options = array();
                if (isset($decoratorInfo['decorator'])) {
                    $decorator = $decoratorInfo['decorator'];
                    if (isset($decoratorInfo['options'])) {
                        $options = $decoratorInfo['options'];
                    }
                    $this->addDecorator($decorator, $options);
                } else {
                    switch (true) {
                        case (0 == $argc):
                            break;
                        case (1 <= $argc):
                            $decorator  = array_shift($decoratorInfo);
                        case (2 <= $argc):
                            $options = array_shift($decoratorInfo);
                        default:
                            $this->addDecorator($decorator, $options);
                            break;
                    }
                }
            } else {
                require_once 'Zend/Form/Exception.php';
                throw new Zend_Form_Exception('Invalid decorator passed to addDecorators()');
            }
        }

        return $this;
    }

    /**
     * Overwrite all decorators
     *
     * @param  array $decorators
     * @return Hosh_List
     */
    public function setDecorators(array $decorators)
    {
        $this->clearDecorators();
        return $this->addDecorators($decorators);
    }

    /**
     * Retrieve a registered decorator
     *
     * @param  string $name
     * @return false|Hosh_List_Decorator_Abstract
     */
    public function getDecorator($name)
    {
        if (!isset($this->_decorators[$name])) {
            $len = strlen($name);
            foreach ($this->_decorators as $localName => $decorator) {
                if ($len > strlen($localName)) {
                    continue;
                }

                if (0 === substr_compare($localName, $name, -$len, $len, true)) {
                    if (is_array($decorator)) {
                        return $this->_loadDecorator($decorator, $localName);
                    }
                    return $decorator;
                }
            }
            return false;
        }

        if (is_array($this->_decorators[$name])) {
            return $this->_loadDecorator($this->_decorators[$name], $name);
        }

        return $this->_decorators[$name];
    }

    /**
     * Retrieve all decorators
     *
     * @return array
     */
    public function getDecorators()
    {
        foreach ($this->_decorators as $key => $value) {
            if (is_array($value)) {
                $this->_loadDecorator($value, $key);
            }
        }
        return $this->_decorators;
    }

    /**
     * Lazy-load a decorator
     *
     * @param  array $decorator Decorator type and options
     * @param  mixed $name Decorator name or alias
     * @return Hosh_List_Decorator_Interface
     */
    protected function _loadDecorator(array $decorator, $name)
    {
        $sameName = false;
        if ($name == $decorator['decorator']) {
            $sameName = true;
        }

        $instance = $this->_getDecorator($decorator['decorator'], $decorator['options']);
        if ($sameName) {
            $newName            = get_class($instance);
            $decoratorNames     = array_keys($this->_decorators);
            $order              = array_flip($decoratorNames);
            $order[$newName]    = $order[$name];
            $decoratorsExchange = array();
            unset($order[$name]);
            asort($order);
            foreach ($order as $key => $index) {
                if ($key == $newName) {
                    $decoratorsExchange[$key] = $instance;
                    continue;
                }
                $decoratorsExchange[$key] = $this->_decorators[$key];
            }
            $this->_decorators = $decoratorsExchange;
        } else {
            $this->_decorators[$name] = $instance;
        }

        return $instance;
    }

    /**
     * Remove a single decorator
     *
     * @param  string $name
     * @return bool
     */
    public function removeDecorator($name)
    {
        $decorator = $this->getDecorator($name);
        if ($decorator) {
            if (array_key_exists($name, $this->_decorators)) {
                unset($this->_decorators[$name]);
            } else {
                $class = get_class($decorator);
                if (!array_key_exists($class, $this->_decorators)) {
                    return false;
                }
                unset($this->_decorators[$class]);
            }
            return true;
        }

        return false;
    }

    /**
     * Clear all decorators
     *
     * @return Zend_Form
     */
    public function clearDecorators()
    {
        $this->_decorators = array();
        return $this;
    }

    /**
     * Set plugin loader to use for validator or filter chain
     *
     * @param  Zend_Loader_PluginLoader_Interface $loader
     * @param  string $type 'decorator', 'filter', or 'validate'
     * @return Hosh_List_Element
     * @throws Zend_Form_Exception on invalid type
     */
    public function setPluginLoader(Zend_Loader_PluginLoader_Interface $loader, $type)
    {
        $type = strtoupper($type);
        switch ($type) {
            case self::DECORATOR:
            case self::FILTER:
            case self::ELEMENT:
                $this->_loaders[$type] = $loader;
                return $this;
            default:
                require_once 'Zend/Form/Exception.php';
                throw new Zend_Form_Exception(sprintf('Invalid type "%s" provided to setPluginLoader()', $type));
        }
    }

    /**
     * Retrieve plugin loader for given type
     *
     * $type may be one of:
     * - decorator
     * - element
     *
     * If a plugin loader does not exist for the given type, defaults are
     * created.
     *
     * @param  string $type
     * @return Zend_Loader_PluginLoader_Interface
     */
    public function getPluginLoader($type = null)
    {
        $type = strtoupper($type);
        if (!isset($this->_loaders[$type])) {
            switch ($type) {
                case self::DECORATOR:
                    $prefixSegment = 'List_Decorator';
                    $pathSegment   = 'List/Decorator';
                    break;
                case self::ELEMENT:
                    $prefixSegment = 'List_Element';
                    $pathSegment   = 'List/Element';
                    break;
                case self::FILTER:
                    $prefixSegment = ucfirst(strtolower($type));
                    $pathSegment   = $prefixSegment;
                    break;
                default:
                    require_once 'Zend/Form/Exception.php';
                    throw new Zend_Form_Exception(sprintf('Invalid type "%s" provided to getPluginLoader()', $type));
            }

            require_once 'Zend/Loader/PluginLoader.php';
            $this->_loaders[$type] = new Zend_Loader_PluginLoader(
                array('Hosh_' . $prefixSegment . '_' => 'Hosh/' . $pathSegment . '/')
            );
        }

        return $this->_loaders[$type];
    }


    /**
     * Add prefix path for plugin loader
     *
     * If no $type specified, assumes it is a base path for both filters and
     * validators, and sets each according to the following rules:
     * - decorators: $prefix = $prefix . '_Decorator'
     * - filters: $prefix = $prefix . '_Filter'
     *
     * Otherwise, the path prefix is set on the appropriate plugin loader.
     *
     * @param  string $prefix
     * @param  string $path
     * @param  string $type
     * @return Hosh_List_Element
     * @throws Zend_Form_Exception for invalid type
     */
    public function addPrefixPath($prefix, $path, $type = null)
    {
        $type = strtoupper($type);
        switch ($type) {
            case self::DECORATOR:
            case self::FILTER:
                $loader = $this->getPluginLoader($type);
                $loader->addPrefixPath($prefix, $path);
                return $this;
            case null:
                $nsSeparator = (false !== strpos($prefix, '\\'))?'\\':'_';
                $prefix = rtrim($prefix, $nsSeparator) . $nsSeparator;
                $path   = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                foreach (array(self::DECORATOR, self::FILTER) as $type) {
                    $cType        = ucfirst(strtolower($type));
                    $loader       = $this->getPluginLoader($type);
                    $loader->addPrefixPath($prefix . $cType, $path . $cType . DIRECTORY_SEPARATOR);
                }
                return $this;
            default:
                require_once 'Zend/Form/Exception.php';
                throw new Zend_Form_Exception(sprintf('Invalid type "%s" provided to getPluginLoader()', $type));
        }
    }

    /**
     * Add many prefix paths at once
     *
     * @param  array $spec
     * @return Hosh_List_Element
     */
    public function addPrefixPaths(array $spec)
    {
        if (isset($spec['prefix']) && isset($spec['path'])) {
            return $this->addPrefixPath($spec['prefix'], $spec['path']);
        }
        foreach ($spec as $type => $paths) {
            if (is_numeric($type) && is_array($paths)) {
                $type = null;
                if (isset($paths['prefix']) && isset($paths['path'])) {
                    if (isset($paths['type'])) {
                        $type = $paths['type'];
                    }
                    $this->addPrefixPath($paths['prefix'], $paths['path'], $type);
                }
            } elseif (!is_numeric($type)) {
                if (!isset($paths['prefix']) || !isset($paths['path'])) {
                    foreach ($paths as $prefix => $spec) {
                        if (is_array($spec)) {
                            foreach ($spec as $path) {
                                if (!is_string($path)) {
                                    continue;
                                }
                                $this->addPrefixPath($prefix, $path, $type);
                            }
                        } elseif (is_string($spec)) {
                            $this->addPrefixPath($prefix, $spec, $type);
                        }
                    }
                } else {
                    $this->addPrefixPath($paths['prefix'], $paths['path'], $type);
                }
            }
        }
        return $this;
    }

    /**
     * Render list
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        if (null !== $view) {
            $this->setView($view);
        }

        $content = '';
        foreach ($this->getDecorators() as $decorator) {
            $decorator->setElement($this);
            $content = $decorator->render($content);
        }
        $this->_setIsRendered();
        return $content;
    }

    /**
     * Serialize as string
     *
     * Proxies to {@link render()}.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $return = $this->render();
            return $return;
        } catch (Exception $e) {
            $message = "Exception caught by form: " . $e->getMessage()
                . "\nStack Trace:\n" . $e->getTraceAsString();
            trigger_error($message, E_USER_WARNING);
            return '';
        }
    }


    /**
     * When calling renderFormElements or render this method
     * is used to set $_isRendered member to prevent repeatedly
     * merging belongsTo setting
     */
    protected function _setIsRendered()
    {
        $this->_isRendered = true;
        return $this;
    }

    /**
     * Get the value of $_isRendered member
     */
    protected function _getIsRendered()
    {
        return (bool)$this->_isRendered;
    }


    // Localization:

    /**
     * Set translator object for localization
     *
     * @param  Zend_Translate|null $translator
     * @return Hosh_List_Element
     */
    public function setTranslator($translator = null)
    {
        if (null === $translator) {
            $this->_translator = null;
        } elseif ($translator instanceof Zend_Translate_Adapter) {
            $this->_translator = $translator;
        } elseif ($translator instanceof Zend_Translate) {
            $this->_translator = $translator->getAdapter();
        } else {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Invalid translator specified');
        }
        return $this;
    }

    /**
     * Retrieve localization translator object
     *
     * @return Zend_Translate_Adapter|null
     */
    public function getTranslator()
    {
        return $this->_translator;
    }

    /**
     * Does this element have its own specific translator?
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return (bool)$this->_translator;
    }

}