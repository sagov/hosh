<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Alert.php 21.04.2016 18:04:52
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Alert
 *
 * @category Hosh
 * @package Hosh_Controller
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Controller_Action_Helper_Alert extends Zend_Controller_Action_Helper_Abstract
{

    /**
     *
     * @var unknown
     */
    protected $_alert;

    private $_sess_namespace = 'hoshalert';

    /**
     *
     * @var unknown
     */
    protected static $_instance = null;

    /**
     */
    public function __construct ()
    {
        $text = $this->_getsession();
        if (! empty($text)) {
            $this->_alert[][] = $text;
        }
    }

    /**
     *
     * @return Hosh_Controller_Action_Helper_Alert
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *
     * @param string $string            
     * @param string $code            
     * @return Hosh_Controller_Action_Helper_Alert
     */
    public function add ($string, $code = null)
    {
        $this->_alert[$code][] = $string;
        return $this;
    }

    /**
     *
     * @param string $string            
     * @param string $code            
     * @param number $hops            
     * @return Hosh_Controller_Action_Helper_Alert
     */
    public function addsession ($string, $code = null, $hops = 1)
    {
        $this->add($string, $code);
        $s = Hosh_Session::getInstanse($this->_sess_namespace);
        $adapter = $s->getAdapter();
        $adapter->setExpirationHops($hops);
        
        $s->set('alert', $string);
        return $this;
    }

    /**
     *
     * @return unknown
     */
    public function get ()
    {
        return $this->_alert;
    }

    /**
     *
     * @return NULL
     */
    protected function _getsession ()
    {
        $s = Hosh_Session::getInstanse($this->_sess_namespace);
        return $s->get('alert');
    }
}