<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Default.php 07.04.2016 11:24:46
 */
require_once 'Hosh/Manager/Acl/Role/AdapterAbstract.php';
/**
 * Adapter Acl role Default
 * 
 * @category   Hosh
 * @package    Hosh_Manager
 * @subpackage Acl 
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Acl_Role_Adapter_Default extends Hosh_Manager_Acl_Role_AdapterAbstract
{    
    
    
    /* (non-PHPdoc)
     * @see Hosh_Manager_Acl_Role_AdapterAbstract::getList()
     */
    public function getList ($filter = null)
    {
        static $result;
        $key = Zend_Json::encode($filter);
        if (isset($result[$key])) {
            return $result[$key];
        }
        
        $package = new Hosh_Manager_Db_Package_Hosh_Acl_Role();
        $result[$key] = $package->getList($filter);
        return $result[$key];
    }

    /* (non-PHPdoc)
     * @see Hosh_Manager_Acl_Role_AdapterAbstract::getObject()
     */
    public function getObject ($id)
    {
        static $result;
        
        if (isset($result[$id])) {
            return $result[$id];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_Acl_Role();
        $result[$id] = $package->getObject($id);
        return $result[$id];
    }
}