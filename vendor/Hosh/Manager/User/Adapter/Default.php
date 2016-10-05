<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @subpackage  User
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Default.php 07.04.2016 14:42:51
 */
require_once 'Hosh/Manager/User/AdapterAbstract.php';

/**
 * User Adapter Default
 *
 * @category Hosh
 * @package Hosh_Manager
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_User_Adapter_Default extends Hosh_Manager_User_AdapterAbstract
{
    
    /*
     * (non-PHPdoc) @see Hosh_Manager_User_AdapterAbstract::getObject()
     */
    public function getObject ($id)
    {
        static $result;
        if (isset($result[$id])) {
            return $result[$id];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_User();
        $result[$id] = $package->getObject($id);
        return $result[$id];
    }
    
    /*
     * (non-PHPdoc) @see Hosh_Manager_User_AdapterAbstract::getAclRoles()
     */
    public function getAclRoles ($id)
    {
        static $result;
        if (isset($result[$id])) {
            return $result[$id];
        }
        
        $package = new Hosh_Manager_Db_Package_Hosh_User_Role();
        $result[$id] = $package->getList(
                array(
                        'iduser' => $id
                ));
        return $result[$id];
    }

    /* (non-PHPdoc)
     * @see Hosh_Manager_User_AdapterAbstract::getList()
     */
    public function getList ($filter= null, $count = null, $offset = 0)
    {
        static $result;
        $key = Zend_Json::encode(array(
                $filter,
                $count,
                $offset
        ));
        if (isset($result[$key])) {
            return $result[$key];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_User();
        $result[$key] = $package->getList($filter, $count, $offset);
        return $result[$key];
    }
    
    /* (non-PHPdoc)
     * @see Hosh_Manager_User_AdapterAbstract::getTotal()
     */
    public function getTotal($filter = null)
    {
        static $result;
        $key = Zend_Json::encode(array(
                $filter                
        ));
        if (isset($result[$key])) {
            return $result[$key];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_User();
        $result[$key] = $package->getTotal($filter);
        return $result[$key];
    }
    
    public function getAuthUser()
    {
        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();
        return $user;
    }
}