<?php

/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Menu.php 08.04.2016 10:50:13
 */

/**
 * Menu
 *
 * @category Hosh
 * @package Hosh_Manager
 * @subpackage Menu
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_Menu
{

    /**
     * @param string $adapter
     * @throws Zend_Exception
     * @return Hosh_Manager_Menu_AdapterAbstract
     */
    public function getAdapter ($adapter)
    {
        static $result;
        
        if (isset($result)){
            return $result;
        }
        
        $adapterName = 'Hosh_Manager_Menu_Adapter_';
        $adapterName .= str_replace(' ', '_', 
                ucwords(str_replace('_', ' ', strtolower($adapter))));
        if (! class_exists($adapterName)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($adapterName);
        }
        
        $result = new $adapterName();
        
        if (! $result instanceof Hosh_Manager_Menu_AdapterAbstract) {
            
            require_once 'Zend/Exception.php';
            throw new Zend_Exception(
                    "Adapter class '$adapterName' does not extend Hosh_Manager_Menu_AdapterAbstract");
        }
        return $result;
    }
}