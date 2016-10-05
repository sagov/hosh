<?php

/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Session
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Joomla.php 27.06.2016 15:23:11
 */
require_once 'Hosh/Session/AdapterAbstract.php';
/**
 * Hosh Session Adapter Joomla
 *
 * @category Hosh
 * @package Hosh_Session
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Session_Adapter_Joomla extends Hosh_Session_AdapterAbstract
{

    public function __construct ($namespace = null)
    {
        parent::__construct($namespace);
        $this->Prepend();
    }

    public function get ($key, $default = null)
    {
        $session = & JFactory::getSession();
        return $session->get($key, $default, $this->_namespace);
    }

    public function set ($key, $value)
    {
        $session = & JFactory::getSession();
        $session->set($key, $value, $this->_namespace);
        return $this;
    }

    /**
     * setExpirationHops() - expire the namespace, or specific variables after a
     * specified
     * number of page hops
     *
     * @param int $hops
     *            - how many "hops" (number of subsequent requests) before
     *            expiring
     * @param mixed $variables
     *            - OPTIONAL list of variables to expire (defaults to all)
     * @param boolean $hopCountOnUsageOnly
     *            - OPTIONAL if set, only count a hop/request if this namespace
     *            is used
     * @throws Zend_Session_Exception
     * @return void
     */
    public function setExpirationHops ($hops, $variables = null, 
            $hopCountOnUsageOnly = true)
    {
        if ($hops <= 0) {
            /**
             *
             * @see Zend_Session_Exception
             */
            require_once 'Zend/Session/Exception.php';
            throw new Zend_Session_Exception('Hops must be positive number.');
        }
        
        if ($variables === null) {
            
            // apply expiration to entire namespace
            if ($hopCountOnUsageOnly === false) {
                $_SESSION['__ZF'][$this->_namespace]['ENGH'] = $hops;
            } else {
                $_SESSION['__ZF'][$this->_namespace]['ENNH'] = $hops;
            }
        } else {
            
            if (is_string($variables)) {
                $variables = array(
                        $variables
                );
            }
            
            foreach ($variables as $variable) {
                if (! empty($variable)) {
                    if ($hopCountOnUsageOnly === false) {
                        $_SESSION['__ZF'][$this->_namespace]['ENVGH'][$variable] = $hops;
                    } else {
                        $_SESSION['__ZF'][$this->_namespace]['ENVNH'][$variable] = $hops;
                    }
                }
            }
        }
        
        return $this;
    }

    private function Prepend ()
    {
        if (isset($_SESSION['__ZF'][$this->_namespace])) {
            
            // Expire Namespace by Namespace Hop (ENNH)
            if (isset($_SESSION['__ZF'][$this->_namespace]['ENNH'])) {
                $_SESSION['__ZF'][$this->_namespace]['ENNH'] --;
                
                if ($_SESSION['__ZF'][$this->_namespace]['ENNH'] === 0) {
                    $session = & JFactory::getSession();
                    $session->clear(null, $this->_namespace);
                    unset($_SESSION['__ZF'][$this->_namespace]);
                }
            }
            
            // Expire Namespace Variables by Namespace Hop (ENVNH)
            if (isset($_SESSION['__ZF'][$this->_namespace]['ENVNH'])) {
                foreach ($_SESSION['__ZF'][$this->_namespace]['ENVNH'] as $variable => $hops) {
                    $_SESSION['__ZF'][$this->_namespace]['ENVNH'][$variable] --;
                    
                    if ($_SESSION['__ZF'][$this->_namespace]['ENVNH'][$variable] ===
                             0) {
                        $session = & JFactory::getSession();
                        $session->clear(null, $this->_namespace);
                        unset(
                                $_SESSION['__ZF'][$this->_namespace]['ENVNH'][$variable]);
                    }
                }
                if (empty($_SESSION['__ZF'][$this->_namespace]['ENVNH'])) {
                    unset($_SESSION['__ZF'][$this->_namespace]['ENVNH']);
                }
            }
        }
        
        if (empty($_SESSION['__ZF'][$this->_namespace])) {
            unset($_SESSION['__ZF'][$this->_namespace]);
        }
        
        if (empty($_SESSION['__ZF'])) {
            unset($_SESSION['__ZF']);
        }
        
        return $this;
    }
}