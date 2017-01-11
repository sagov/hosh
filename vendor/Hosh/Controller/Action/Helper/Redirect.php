<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Redirect.php 21.04.2016 18:17:38
 */
require_once 'Zend/Controller/Action/Helper/Redirector.php';

/**
 * Redirect
 *
 * @category Hosh
 * @package Hosh_Controller
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Controller_Action_Helper_Redirect extends Zend_Controller_Action_Helper_Redirector
{

    /**
     *
     * @param string $url            
     * @param string $string            
     * @param string $code            
     * @param array $options            
     */
    public function redirect ($url, $string = null, $code = null, 
            array $options = array())
    {
        if (! empty($string)) {
            $alert = Hosh_Controller_Action_Helper_Alert::getInstance();
            $alert->addsession($string, $code);
        }
        $controller = $this->getFrontController();
        $controller->setResponse(new Zend_Controller_Response_Http());
        $this->gotoUrlAndExit($url, $options);
    }
}