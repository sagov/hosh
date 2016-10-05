<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Session
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Default.php 27.06.2016 15:23:11
 */
require_once 'Hosh/Session/AdapterAbstract.php';
/**
 * Hosh Session Adapter Default
 * 
 * @category   Hosh
 * @package    Hosh_Session
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Session_Adapter_Default extends Hosh_Session_AdapterAbstract
{    

    public function get ($key, $default = null)
    {
        $session = $this->getSession();
        $result =  $session->__get($key);
        return (isset($result)) ? $result : $default;
    }

    public function set ($key, $value)
    {
        $session = $this->getSession();
        $session->__set($key, $value);
        return $this;
    }
    
    protected function getSession()
    {
        static $session;
        if (isset($session[$this->_namespace])){
            return $session[$this->_namespace];
        }
        $session[$this->_namespace] = new Zend_Session_Namespace($this->_namespace);
        return $session[$this->_namespace];
    }
    
    public function setExpirationHops($hops, $variables = null, $hopCountOnUsageOnly = false)
    {
        $session = $this->getSession();
        $session->setExpirationHops($hops,$variables,$hopCountOnUsageOnly);
        return $this;
    }
}