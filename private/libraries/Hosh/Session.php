<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Session
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Session.php 27.06.2016 15:19:22
 */

/**
 * Hosh Session
 * 
 * @category   Hosh
 * @package     Hosh_Session
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Session
{

    protected $_adapter = 'Default';

    protected $_namespace = 'hosh';

    protected static $_instance = null;

    /**
     *
     * @param string $namespace            
     */
    public function __construct ($namespace = null)
    {
        if (! empty($namespace)) {
            $this->_namespace = $namespace;
        }
    }

    /**
     *
     * @param string $namespace            
     * @return Hosh_Session
     */
    public static function getInstanse ($namespace = null)
    {
        if (! isset(self::$_instance[$namespace])) {
            self::$_instance[$namespace] = new self($namespace);
            $config = Hosh_Config::getInstance();
            $adapter = $config->get('adapter');
            if (! empty($adapter)) {
                self::$_instance[$namespace]->_adapter = $adapter;
            }
        }
        
        return self::$_instance[$namespace];
    }

    /**
     * @throws Zend_Db_Exception
     * @return unknown|Hosh_Session_AdapterAbstract
     */
    public function getAdapter ()
    {
        static $result;
        
        if (isset($result[$this->_namespace])) {
            return $result[$this->_namespace];
        }
        
        $adapter = $this->_adapter;
        
        $adapterName = 'Hosh_Session_Adapter_';
        $adapterName .= str_replace(' ', '_', 
                ucwords(str_replace('_', ' ', strtolower($adapter))));
        
        if (! class_exists($adapterName)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($adapterName);
        }
        $result[$this->_namespace] = new $adapterName($this->_namespace);
        if (! $result[$this->_namespace] instanceof Hosh_Session_AdapterAbstract) {
            require_once 'Zend/Db/Exception.php';
            throw new Zend_Db_Exception(
                    "Adapter class '$adapterName' does not extend Hosh_Session_AdapterAbstract");
        }
        
        return $result[$this->_namespace];
    }

    /**
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public function get ($key, $default = null)
    {
        $adapter = $this->getAdapter();
        return $adapter->get($key,$default);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Hosh_Session
     */
    public function set ($key, $value)
    {
        $adapter = $this->getAdapter();
        $adapter->set($key, $value);
        return $this;
    }
}