<?php

class Hosh_Manager_Language_Self extends Hosh_Manager_Language
{
    protected $_code = 'en';
    
    protected static $_instance = null;
    
    public function __construct()
    {
        $s = Hosh_Session::getInstanse();
        $code = $s->get('lang.code',$this->_code);       
        if (!empty($code)){
            $this->_code = $code;
        }
    } 

    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();            
        }
        return self::$_instance;
    }
    
    public function getCode()
    {
        return $this->_code;
    }
    
    public function setCode($code)
    {
        $this->_code = $code;
        return $this;
    }
    
    public function getData()
    {
        return $this->_get($this->_code);
    }
    
    public function set($code)
    {
        $s = Hosh_Session::getInstanse();
        $s->set('lang.code', $code);        
        $this->setCode($code);
        return $this;
    }
}