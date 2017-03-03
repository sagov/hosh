<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Factory.php 03.06.2016 10:35:11
 */
/** @see Zend_Form */
require_once 'Zend/Form.php';
/**
 * Hosh Form Factory
 * 
 * @category   Hosh
 * @package     Hosh_Form
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Form_Factory extends Zend_Form
{

    /**
     * #@+
     * Plugin loader type constants
     */
    const HELPER = 'HELPER';

    const PATTERN = 'PATTERN';

    const FILTER = 'FILTER';

    const VALIDATE = 'VALIDATE';

    protected $_settings = array(
            'plugin' => array(
                    'path' => '',
                    'prefix' => 'HoshPluginForm_'
            ),
            'pattern' => array(
                    'adapter' => 'hoshmanager',
                    'data' => '',
            ),
            'prefixPath' => array(
                    'prefix' => 'Hosh_Form_',
                    'path' => 'Hosh/Form/'
            ),
            'translate' => null,
            'initHelper' => 'init',
            'acl' => true,
            'headlink' => true,
            'headscript' => true,
            'headmeta' => true,
            'is_layout' => true,
            'tooltip' => true,
            'updateparams' => false,
            'bRequest' => true,
            'actionpost' => 'actionpost_',
            'isEditHelper' => 'isEdit',
            'attribs' => array(
                    'idpreff' => 'hoshform_',
                    'namepreff' => 'hoshform_'
            ),
            'decoratorHelper' => 'Decorator_Form',
            'decoratorElementHelper' => 'Decorator_Element'
    );

    /**
     * индетификатор формы
     *
     * @var string
     */
    protected $_idself;

    /**
     * Pattern Form
     *
     * @var object
     */
    protected $_pattern;

    /**
     * Access Form
     *
     * @var boolean
     */
    protected $_isaccess = true;

    /**
     * Public Form
     *
     * @var boolean
     */
    protected $_bpublic  = 0;

    /**
     *
     * @var boolean
     */
    protected $_isedit = false;

    /**
     * Data Form
     *
     * @var mixed
     */
    protected $_data;

    /**
     * Plugin
     *
     * @var array
     */
    protected $_plugin;

    /**
     *
     * @param string $idself            
     * @param array $settings            
     * @throws Zend_Form_Exception
     * @return boolean
     */
    function __construct ($idself, $settings = null)
    {
        if (empty($idself)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(sprintf('Idself empty'));
            return false;
        }
        
        $this->_idself = $idself;
        
        if (is_array($settings)) {
            $this->setSettings($settings);
        }
        $this->_plugin = $this->getSettingPlugin($this->_idself);
        parent::__construct();
    }

    /**
     * Get Id
     *
     * @return string
     */
    public function getIdSelf ()
    {
        return $this->_idself;
    }

    /**
     *
     * @param string $id            
     * @return array
     */
    public function getSettingPlugin ($id)
    {
        $path = str_replace('_', '/', $id);
        $config = Hosh_Config::getInstance();
        $path_source = $config->get('path');
        $settings = array();
        if (empty($this->_settings['plugin']['path'])){
            $this->_settings['plugin']['path'] = $config->get('form')->get('plugin')->path;
        }
        $settings['path'] = $this->_settings['plugin']['path']. $path;
        $settings['prefix'] = $this->_settings['plugin']['prefix'] . $id . '_';
        return $settings;
    }

    /**
     *
     * @param string $key            
     * @param mixed $value            
     * @return Hosh_Form_Factory
     */
    public function setSetting ($key, $value)
    {
        $this->_settings[$key] = $value;
        return $this;
    }

    /**
     *
     * @param string $key            
     * @param mixed $default            
     * @return mixed
     */
    public function getSetting ($key, $default = null)
    {
        if (isset($this->_settings[$key])) {
            return $this->_settings[$key];
        }
        return $default;
    }

    /**
     *
     * @param array $array            
     * @param string $key            
     * @return Hosh_Form_Factory
     */
    public function setSettings (array $array, $key = null)
    {
        if (! empty($key)) {
            if (isset($this->_settings[$key]) and
                     (is_array($this->_settings[$key]))) {
                $this->_settings[$key] = array_merge($this->_settings[$key], 
                        $array);
            } else {
                $this->_settings[$key] = $array;
            }
        } else {
            $this->_settings = array_merge($this->_settings, $array);
        }
        return $this;
    }

    /**
     *
     * @param string $key            
     * @param string $default            
     * @return array
     */
    public function getSettings ($key = null, $default = false)
    {
        if (isset($key)) {
            if (isset($this->_settings[$key])) {
                return $this->_settings[$key];
            } else {
                return $default;
            }
        }
        return $this->_settings;
    }

    /**
     *
     * @return Hosh_Form_Factory
     */
    public function initialize ()
    {
        require_once 'Hosh/View.php';
        $this->setView(Hosh_View::getInstance());
        
        $this->setPlugin();
        $this->getHelper('init');
        $this->_isEdit()
            ->_initTranslate()
            ->_setAccess();
        return $this;
    }

    protected function _initTranslate ()
    {
        $_translate = $this->getSettings('translate');
        if (isset($_translate)) {
            $h_translate = Hosh_Translate::getInstance();
            $translate = $h_translate->getTranslate();
        } elseif ($_translate instanceof Zend_Translate) {
            $translate = $_translate;
        } elseif ($_translate instanceof Zend_Translate_Adapter) {
            $translate = $_translate;
        } else {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(
                    sprintf('Translate ' . $_translate . ' not define'));
        }
        $this->setTranslator($translate);
        $this->addTranslation('form/_');
        $idself = $this->getIdSelf();
        $content = 'form/' . $idself;
        $this->addTranslation($content);
        $this->getHelperPattern('translate');
        
        return $this;
    }

    /**
     *
     * @param mixed $content            
     * @return Hosh_Form_Factory
     */
    public function addTranslation ($content)
    {
        $translator = $this->getTranslator();
        $locale = $translator->getLocale();
        if (is_array($content)) {
            foreach ($content as $val) {
                $translator->addTranslation(trim($val), $locale);
            }
        } else {
            $translator->addTranslation(trim($content), $locale);
        }
        return $this;
    }

    /**
     *
     * @return Hosh_Form_Pattern_Abstract
     */
    public function getPattern ()
    {
        if (isset($this->_pattern)) {
            return $this->_pattern;
        }
        $pattern = $this->loadPatternAdapter();
        $pattern->render();
        $this->_pattern = $pattern;
        return $this->_pattern;
    }

    /**
     *
     * @throws Zend_Form_Exception
     * @return Hosh_Form_Pattern_Abstract
     */
    public function loadPatternAdapter ()
    {
        $patternsettings = $this->getSettings('pattern');
        if (empty($patternsettings['adapter'])) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(sprintf('Pattern adapter not found'));
        }
        
        $adapter = $patternsettings['adapter'];
        $adapterName = 'Adapter_';
        $adapterName .= str_replace(' ', '_', 
                ucwords(str_replace('_', ' ', strtolower($adapter))));
        
        if ($class = $this->getPluginLoaderHosh(self::PATTERN)->load(
                $adapterName)) {
            return new $class($this);
        } else {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(
                    sprintf('Invalid pattern adapter "%s"', $adapter));
        }
        return;
    }

    /**
     *
     * @return boolean
     */
    public function isAccess ()
    {
        return $this->_isaccess;
    }

    /**
     *
     * @return Hosh_Form_Factory
     */
    protected function _setAccess ()
    {
        $flag = 1;
        $public = $this->_getPublic();
        $this->setPublic($public);
        if (empty($public)){
            $this->setAccess($public);
            return $this;
        }

        if ($this->getSettings('acl')) {
            if (! $access = $this->getHelperPattern('acl')) {
                $flag = 0;
            }
        }
        $this->setAccess($flag);
        return $this;
    }

    /**
     *
     * @param integer $flag            
     * @return Hosh_Form_Factory
     */
    public function setAccess ($flag)
    {
        $this->_isaccess = (boolean) ($flag);
        return $this;
    }

    /**
     *
     * @param integer $value
     * @return Hosh_Form_Factory
     */
    public function setPublic($value)
    {
        $this->_bpublic = $value;
        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getPublic()
    {
        return $this->_bpublic;
    }

    /**
     *
     * @param mixed $pattern_data
     * @return Ambigous <number, boolean, unknown>
     */
    protected function _getPublic ($pattern_data = null)
    {
        $public = 0;
        if (! isset($pattern_data)) {
            $patternclass = $this->getPattern();
            $pattern_data = $patternclass->getData();
        }
        if (isset($pattern_data['bpublic'])) {
            $public = $pattern_data['bpublic'];
        }
        if (! empty($pattern_data['public']['helper'])) {
            $public_helper = $this->getHelper($pattern_data['public']['helper'],
                $pattern_data['public']);
            if (isset($public_helper)) {
                $public = $public_helper;
            }
        }
        return $public;
    }

    /**
     *
     * @return boolean
     */
    public function isEdit ()
    {
        return $this->_isedit;
    }

    /**
     *
     * @return Hosh_Form_Factory
     */
    protected function _isEdit ()
    {
        $result = 0;
        $helper = $this->getSettings('isEditHelper');
        if (! empty($helper)) {
            $result_helper = $this->getHelper($helper);
            if ($result_helper) {
                $result = 1;
            }
        }
        $this->setIsEdit($result);
        return $this;
    }

    /**
     *
     * @param integer $flag            
     * @return Hosh_Form_Factory
     */
    public function setIsEdit ($flag)
    {
        $this->_isedit = (boolean) ($flag);
        return $this;
    }

    /**
     *
     * @param string $plugin            
     * @throws Zend_Form_Exception
     * @return Hosh_Form_Factory
     */
    public function setPlugin ($plugin = null)
    {
        if (! empty($plugin['path'])) {
            $this->_plugin['path'] = $plugin['path'];
        }
        if (! empty($plugin['prefix'])) {
            $this->_plugin['prefix'] = $plugin['prefix'];
        }
        
        $path = $this->_plugin['path'];
        $prefix = $this->_plugin['prefix'];
        
        if (is_dir($path)) {
            $pluginLoader = new Zend_Loader_PluginLoader(
                    array(
                            $prefix => $path
                    ));
            $this->addPrefixPathHosh($prefix, $path);
        } else 
            if (! empty($plugin)) {
                require_once 'Zend/Form/Exception.php';
                throw new Zend_Form_Exception(
                        sprintf('Invalid plugin path  "%s" to setPlugin()', 
                                $path));
            }
        return $this;
    }

    /**
     *
     * @return multitype:
     */
    public function getPlugin ()
    {
        return $this->_plugin;
    }

    /**
     * Add prefix path for plugin loader
     *
     * If no $type specified, assumes it is a base path for both filters and
     * validators, and sets each according to the following rules:
     * - decorators: $prefix = $prefix . '_Decorator'
     * - elements: $prefix = $prefix . '_Element'
     * - helper: $prefix = $prefix . '_Helper'
     * - pattern: $prefix = $prefix . '_Pattern'
     *
     * Otherwise, the path prefix is set on the appropriate plugin loader.
     *
     * If $type is 'decorator', sets the path in the decorator plugin loader
     * for all elements. Additionally, if no $type is provided,
     * the prefix and path is added to both decorator and element
     * plugin loader with following settings:
     * $prefix . '_Decorator', $path . '/Decorator/'
     * $prefix . '_Element', $path . '/Element/'
     * $prefix . '_Helper', $path . '/Helper/'
     * $prefix . '_Pattern', $path . '/Pattern/'
     *
     * @param string $prefix            
     * @param string $path            
     * @param string $type            
     * @return Hosh_Form
     * @throws Zend_Form_Exception for invalid type
     */
    private function addPrefixPathHosh ($prefix, $path, $type = null)
    {
        $type = strtoupper($type);
        
        switch ($type) {
            case self::HELPER:
            case self::PATTERN:
            case self::DECORATOR:
            case self::ELEMENT:
                $loader = $this->getPluginLoaderHosh($type);
                $loader->addPrefixPath($prefix, $path);
                break;
            case null:
                
                $nsSeparator = (false !== strpos($prefix, '\\')) ? '\\' : '_';
                $prefix = rtrim($prefix, $nsSeparator);
                $path = rtrim($path, DIRECTORY_SEPARATOR);
                foreach (array(
                        self::DECORATOR,
                        self::ELEMENT,
                        self::HELPER,
                        self::PATTERN
                ) as $type) {
                    //$cType = ucfirst(strtolower($type));
                    $cType = str_replace(' ', '_',
                    ucwords(str_replace('_', ' ', strtolower($type))));
                    
                    $pluginPath = $path . DIRECTORY_SEPARATOR . $cType .
                             DIRECTORY_SEPARATOR;
                    $pluginPrefix = $prefix . $nsSeparator . $cType;                    
                    $loader = $this->getPluginLoaderHosh($type);
                    
                    $loader->addPrefixPath($pluginPrefix, $pluginPath);
                }
                break;
            default:
                require_once 'Zend/Form/Exception.php';
                throw new Zend_Form_Exception(
                        sprintf(
                                'Invalid type "%s" provided to addPrefixPathHosh()', 
                                $type));
                break;
        }
        return $this;
    }

    /**
     * Retrieve plugin loader for given type
     *
     * $type may be one of:
     * - decorator
     * - element
     * - helper
     * - pattern
     *
     * If a plugin loader does not exist for the given type, defaults are
     * created.
     *
     * @param string $type            
     * @return Zend_Loader_PluginLoader_Interface
     */
    public function getPluginLoaderHosh ($type = null)
    {
        $type = strtoupper($type);
        if (! isset($this->_loaders[$type])) {
            switch ($type) {
                case self::HELPER:
                    $prefixSegment = $this->_settings['prefixPath']['prefix'] .
                             'Helper_';
                    $pathSegment = $this->_settings['prefixPath']['path'] .
                             'Helper/';
                    break;
                case self::PATTERN:
                    $prefixSegment = $this->_settings['prefixPath']['prefix'] .
                             'Pattern_';
                    $pathSegment = $this->_settings['prefixPath']['path'] .
                             'Pattern/';
                    break;
               
                
                case self::ELEMENT: 
                    $prefixSegment = $this->_settings['prefixPath']['prefix'] .
                    'Element_';
                    $pathSegment = $this->_settings['prefixPath']['path'] .
                    'Element/';
                    $loader = $this->getPluginLoader($type);
                    $loader->addPrefixPath($prefixSegment, $pathSegment);
                    return $loader;
                    break;
               case self::DECORATOR:
               
                        $prefixSegment = $this->_settings['prefixPath']['prefix'] .
                        'Decorator_';
                        $pathSegment = $this->_settings['prefixPath']['path'] .
                        'Decorator/';
                        $loader = $this->getPluginLoader($type);
                        $loader->addPrefixPath($prefixSegment, $pathSegment);
                        return $loader;
                        break;
                default:
                    require_once 'Zend/Form/Exception.php';
                    throw new Zend_Form_Exception(
                            sprintf(
                                    'Invalid type "%s" provided to getPluginLoaderHosh()', 
                                    $type));
            }
            
            require_once 'Zend/Loader/PluginLoader.php';
            $this->_loaders[$type] = new Zend_Loader_PluginLoader(
                    array(
                            $prefixSegment => $pathSegment
                    ));
        }
        
        return $this->_loaders[$type];
    }

    /**
     *
     * @param string $helper            
     * @param string $options            
     * @param string $element            
     * @return boolean
     */
    public function getHelper ($helper, $options = null, $element = null)
    {
        if (! $this->isAccess()) {
            return false;
        }
        $result = false;
        if (! empty($helper)) {
            $class = $this->getPluginLoaderHosh(self::HELPER)->load($helper, 
                    false);
            if ($class) {
                if ($element === null) {
                    $element = $this;
                }
                $helper_class = new $class($element);
                $result = $helper_class->render($options);
            }
        }
        return $result;
    }

    /**
     *
     * @param string $nameparam            
     * @param string $element            
     * @return boolean
     */
    public function getHelperPattern ($nameparam, $element = null)
    {
        $nameparam = strtolower($nameparam);
        $pattern = $this->getPattern();
        
        if (isset($element)) {
            if ($pattern_el = $pattern->getElement(strtolower($element))) {
                $data = $pattern_el->get($nameparam);
            }
        } else {
            $data = $pattern->get($nameparam);
        }
        
        if (isset($data['helper'])) {
            if (isset($element)) {
                $data['element_name'] = $element;
            }
            $result = $this->getHelper($data['helper'], $data);
            return $result;
        }
        return true;
    }

    /**
     *
     * @param string $key 
     * @param string $default           
     * @return mixed|NULL
     */
    public function getData ($key, $default = null)
    {
        $key = strtolower($key);
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }        
        return $default;
    }

    /**
     *
     * @param string $key            
     * @param mixed $value            
     * @return Hosh_Form_Factory
     */
    public function setData ($key, $value)
    {
        $this->_data[strtolower($key)] = $value;
        return $this;
    }

    /**
     *
     * @param mixed $value            
     * @return Hosh_Form_Factory
     */
    public function setDataAll ($value)
    {
        if (is_array($value)){
            $this->_data = array_change_key_case($value,CASE_LOWER);
        }else{
            $this->_data = $value;
        }

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getDataAll()
    {
        return $this->_data;
    }

    /**
     *
     * @return Hosh_Form_Factory
     */
    protected function _loadData ()
    {
        if (isset($this->_data)) {
            return $this;
        }
        $this->_data = $this->getHelperPattern('data');
        if (is_array($this->_data)){
            $this->_data = array_change_key_case($this->_data,CASE_LOWER);
        }
        return $this;
    }

    /**
     *
     * @return Hosh_Form_Factory
     */
    public function loadData ()
    {
        return $this->_loadData();
    }

    /**
     *
     * @return multitype:NULL
     */
    public function getRequestBindElements ()
    {
        $bind = array();
        foreach ($this->getElements() as $key => $element) {
            $bind[$key] = $element->getValue();
        }
        return $bind;
    }

    /**
     *
     * @return boolean
     */
    public function Save ()
    {
        $result = false;
        if ($this->isEdit()) {
            $result = $this->getHelperPattern('update');
        } else {
            $result = $this->getHelperPattern('insert');
        }
        return $result;
    }

    /**
     *
     * @return boolean
     */
    public function setRequest ()
    {
        // action request
        $flag = false;
        if (! $this->isRequest()) {
            return false;
        }
        $bind = $this->getRequestBindElements();
        if ($this->isValid($bind)) {
            if ($flag = $this->Save()) {
                $this->getHelperPattern('request');
            }
        }
        return $flag;
    }

    /**
     *
     * @return boolean
     */
    public function isRequest ()
    {
        if ($this->_settings['bRequest']) {
            $request_http = new Zend_Controller_Request_Http();
            if (($request_http->isPost()) or ($request_http->isXmlHttpRequest())) {
                $postform = $request_http->getPost(
                        $this->getSettings('actionpost') . $this->getIdSelf());
                if ($postform == $this->getIdSelf()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     *
     * @return Hosh_Form_Factory
     */
    public function setElementsForm ()
    {
        $pattern = $this->getPattern();
        $pattern_data = $pattern->getData();
        $pattern_elements = $pattern->getElements();
        
        if (isset($pattern_elements)) {
            usort($pattern_elements,array("self", "_setSortElement"));
            $i = 10;
            foreach ($pattern_elements as $key => $val) {
                $data_element_pattern = $val->getData();
                $public = $this->_getPublic($data_element_pattern);
                if ($pattern->isDisabled()) {
                    $val->set('type', 'value');
                }
                if (($public == 2) or ($this->_bpublic == 2)) {
                    $val->set('type', 'value');
                }
                $access = true;
                if (empty($public)) {
                    $access = false;
                }
                if ($access) {
                    if (isset($data_element_pattern['acl']['helper'])) {
                        $access = $this->getHelperPattern('acl', 
                                $val->get('name'));
                    }
                }
                
                if ($access) {
                    $val->set('norder', $i);
                    $i += 10;
                    $this->getHelperPattern('prepend', $val->get('name'));
                    $this->addElementForm($val->get('type'), $val->get('name'), 
                            $val->getData());
                    $this->getHelperPattern('append', $val->get('name'));
                }
            }
        }
        if (! empty($this->_settings['actionpost'])) {
            $this->addElement('hidden', 
                    $this->getSettings('actionpost') . $this->getIdSelf(), 
                    array(
                            'DisableLoadDefaultDecorators' => true
                    ));
            $element = $this->getElement(
                    $this->getSettings('actionpost') . $this->getIdSelf());
            $value = $this->getIdSelf();
            $element->setValue($value);
            $element->addDecorator('ViewHelper');
            $element->setOrder(1000000);
        }
        
        return $this;
    }
    
    
    /**
     * @param Hosh_Form_Pattern_Abstract $f1
     * @param Hosh_Form_Pattern_Abstract $f2
     * @return number
     */
    private  function _setSortElement($f1,$f2){
        if($f1->get('norder',0) < $f2->get('norder',0)){
            return -1;
        }elseif($f1->get('norder',0) >= $f2->get('norder',0)) {
            return 1;
        }else {
            return 0;
        }
    }

    /**
     *
     * @param string $type            
     * @param string $name            
     * @param array $options            
     * @return Zend_Form_Element
     */
    public function addElementForm ($type, $name, $options = array())
    {
        $type = str_replace(' ', '_',
        		ucwords(str_replace('_', ' ', strtolower($type))));
        
        $options['options']['prefixPath'] = $this->_settings['prefixPath'];
        if (empty($options['options']['id'])) {
            $options['options']['id'] = $name . '-' . $this->getName();
        }
        $options['options']['form'] = $this;
        if (! empty($options['options']['attribs'])) {
            $attribs = $this->getHelper('Text_Toarray', 
                    array(
                            'value' => $options['options']['attribs']
                    ));
            if (is_array($attribs)) {
                $options['options'] = array_merge($options['options'], $attribs);
            }
            unset($options['options']['attribs']);
        }
        $translator = $this->getTranslator();
        if (isset($options['options']['placeholder'])) {
            $options['options']['placeholder'] = $translator->_(
                    $options['options']['placeholder']);
        }
        if (isset($options['options']['title'])) {
            $options['options']['title'] = $translator->_(
                    $options['options']['title']);
        }
        $this->addElement($type, $name, $options['options']);
        $element = $this->getElement($name);
        $element->setAttrib('form', null);
        $element->addPrefixPath($this->_plugin['prefix'], 
                $this->_plugin['path']);
        $value = null;
        if (! $this->isEdit()) {
            $value = $this->getDefaultValueElement($name);
        } else {
            $value = $this->getData($name);
        }
        $request_http = new Zend_Controller_Request_Http();
        if ($request_http->isPost()) {
            $value = $request_http->getPost($name, $value);
        }
        if (isset($value)) {
            $element->setValue($value);
        } 

        if (! empty($options['notempty'])) {
            $element->setAllowEmpty(false);
            $element->addValidator('NotEmpty', false);
        }        
        
        if (! empty($options['addmultioptions']['helper'])) {
            
            $addmultioptions = $this->getHelperPattern('addmultioptions', 
                    $element->getName());
            
            if (method_exists($element, 'addMultiOptions') and
                     (is_array($addmultioptions))) {
                $element->addMultiOptions($addmultioptions);
            }
        }
        
        if (isset($options['separator'])) {
            if (method_exists($element, 'setSeparator')) {
                $element->setSeparator($options['separator']);
            }
        }        
        
        if (isset($options['norder'])) {
            $element->setOrder($options['norder']);
        }

        // css
        if ($this->getSettings('headlink')) {
            $this->getHelperPattern('css',$element->getName());
        }
        // js
        if ($this->getSettings('headscript')) {
            $this->getHelperPattern('js',$element->getName());
        }
        
        $this->setDecoratorFormElement($element, $options);
        
        $this->getHelperPattern('validator', $element->getName());


        
        return $element;
    }

    /**
     *
     * @param Zend_Form_Element $element            
     * @param array $options            
     * @return Hosh_Form_Factory
     */
    public function setDecoratorFormElement (Zend_Form_Element $element, 
            $options)
    {
        $decorator = null;
        if (! empty($options['decorator'])) {
            $decorator = $options['decorator'];
        }
        $decorator['element_name'] = $element->getName();
        if (! empty($decorator['helper'])) {
            $this->getHelper($decorator['helper'], $decorator);
        } else {
            $this->getHelper(
                    $this->getSettings('decoratorElementHelper', 
                            'Decorator_DefaultElement'), $decorator);
        }
        return $this;
    }

    /**
     *
     * @param string $name            
     * @return Ambigous <NULL, boolean, unknown>
     */
    public function getDefaultValueElement ($name)
    {
        $name = strtolower($name);
        $result = null;
        $pattern = $this->getPattern();
        $elements = $pattern->getElements();
        if (isset($elements[$name])) {
            $element = $elements[$name]->getData();
            if (isset($element['defaultvalue'])) {
                if (is_string($element['defaultvalue'])) {
                    $result = $element['defaultvalue'];
                } else 
                    if (isset($element['defaultvalue']['helper'])) {
                        $result = $this->getHelper(
                                $element['defaultvalue']['helper'], 
                                $element['defaultvalue']);
                    }
            }
        }
        return $result;
    }

    /**
     *
     * @param array $params
     * @return Hosh_Form_Factory
     */
    public function setUpdateVars($params)
    {
        $this->setSetting('updateparams',$params);
        return $this;
    }


    /**
     * @return mixed
     */
    public function getUpdateVars()
    {
        return $this->getSetting('updateparams');
    }



    /**
     * @return Hosh_Form_Factory
     */
    public function run ()
    {
        if (! $this->isAccess()) {
            return $this;
        }
        if ($this->isEdit()) {
            $data = $this->_loadData()->getDataAll();
            $this->setDataAll($data);
        }
        
        $this->getHelperPattern('Prepend');
        $pattern = $this->getPattern();
        $data_pattern = $pattern->getData();
        $options = $pattern->getOptions();
        if (! isset($options)) {
            $options = array();
        }
        $idself = $this->getIdSelf();
        $options['prefixPath'] = $this->_settings['prefixPath'];
        if (! isset($options['id']) and
                 ! empty($this->_settings['attribs']['idpreff'])) {
            $options['id'] = $this->_settings['attribs']['idpreff'] . $idself;
        }
        if (! isset($options['name']) and
                 (! empty($this->_settings['attribs']['namepreff']))) {
            $options['name'] = $this->_settings['attribs']['namepreff'] . $idself;
        }
        $this->setOptions($options);
        $this->setElementsForm();
        
        // decorator Form
        if (! isset($data_pattern['decorator']['helper'])) {
            $pattern->set('decorator', 
                    array(
                            'helper' => $this->getSettings('decoratorHelper')
                    ));
        }
        $this->getHelperPattern('decorator');
        
        // css
        if ($this->getSettings('headlink')) {
            $this->getHelperPattern('css');
        }
        // js
        if ($this->getSettings('headscript')) {
            $this->getHelperPattern('js');
        }
        // meta
        if ($this->getSettings('headmeta')) {
            $this->getHelperPattern('meta');
        }
        // tooltip
        if ($this->getSettings('tooltip')) {
            $this->getHelperPattern('tooltip');
        }
        
        $this->getHelperPattern('displaygroup');
        
        $this->getHelperPattern('Append');
        
        if ($this->getSettings('is_layout')) {
            $this->getHelperPattern('layout');
        }
        
        return $this;
    }
}