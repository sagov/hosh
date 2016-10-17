<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Config
 * @copyright   Copyright (c) 2016 Hosh
 * @version     $Id: Config.php 01.04.2015  16:40:10
 */
require_once 'Zend/Config.php';

/**
 * Hosh Config class
 *
 * @category Hosh
 * @package Hosh_Config
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Config extends Zend_Config
{
    
    const SNAME = 'hosh';

    /**
     * @var unknown
     */
    protected static $_instance = null;

    /**
     *
     * @return Hosh_Config
     */
    public static function getInstance ($config = array())
    {
        if (null === self::$_instance) {
            self::$_instance = new self(array(self::SNAME=>$config), true);
        }
        return self::$_instance;
    }
    
    public function get($name, $default = null)
    {                
        $data = parent::get(self::SNAME,array());
        $result = $data->get($name, $default);        
        return $result;
    }
    
    public function set($name, $value)
    {
        $this->__set($name, array(self::SNAME=>$value));
    }
}