<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Factory
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Factory.php 21.04.2016 17:44:48
 */

/**
 * Factory Hosh Framework
 *
 * @category Hosh
 * @package Hosh_Factory
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Factory
{

    /**
     *
     * @param array $config            
     * @return Hosh_Factory
     */
    public function init (array $config)
    {
        set_include_path(
                ini_get('include_path') . PATH_SEPARATOR . dirName(__FILE__) .
                         '../../');
        
        require_once 'Zend/Loader/Autoloader.php';
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('Hosh_');
        $loader->setFallbackAutoloader(true);
                      
        $zconfig = new Zend_Config(array(),true);
        $hosh_config = Hosh_Config::getInstance($config);
        $hosh_config->merge($zconfig);
        
        return $this;
    }
}