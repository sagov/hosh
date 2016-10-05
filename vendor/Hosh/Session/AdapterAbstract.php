<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Session
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: AdapterAbstract.php 27.06.2016 15:22:08
 */

/**
 * 
 * 
 * @category   Hosh
 * @package     Hosh_Session
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
abstract class Hosh_Session_AdapterAbstract
{
    protected static $_instance = null;
    
    protected $_namespace = 'hosh';
    
    abstract public function get($key);
    
    abstract public function set($key,$value);
    
    abstract public function setExpirationHops($hops);
    
    public function __construct($namespace = null)
    {
        if (!empty($namespace)){
            $this->_namespace = $namespace;
        }
        
    }
    
    public static function getInstanse($namespace = null)
    {
                
        if (!isset(self::$_instance[$namespace])) {
            self::$_instance[$namespace] = new self($namespace);
        }

        return self::$_instance[$namespace];
    }
}