<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: User.php 07.04.2016 14:21:26
 */

/**
 * Users
 *
 * @category Hosh
 * @package Hosh_Manager
 * @subpackage User
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_User
{

    /**
     * Adapter
     * @var string
     */
    protected $_adapter = 'Default';

    /**
     * Id User
     * @var string
     */
    protected $id;

    /**
     * @param string $id
     */
    public function __construct ($id = null)
    {
        $this->id = $id;
    }

    /**
     *
     * @param string $adapter            
     * @return Hosh_Manager_User_AdapterAbstract
     */
    public function getAdapter ($adapter = null)
    {
        static $result;
        
        if (isset($result)) {
            return $result;
        }
        
       if (empty($adapter)) {
            $config = Hosh_Config::getInstance();
            $adapter = $config->get('adapter',$this->_adapter);            
        }
        
        
        $adapterName = 'Hosh_Manager_User_Adapter_';
        $adapterName .= str_replace(' ', '_', 
                ucwords(str_replace('_', ' ', strtolower($adapter))));
        
        if (! class_exists($adapterName)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($adapterName);
        }
        $result = new $adapterName();
        if (! $result instanceof Hosh_Manager_User_AdapterAbstract) {
            require_once 'Zend/Db/Exception.php';
            throw new Zend_Db_Exception(
                    "Adapter class '$adapterName' does not extend Hosh_Manager_User_AdapterAbstract");
        }
        
        return $result;
    }

    /**
     * Get data user
     * 
     * @return array
     */
    public function getData ()
    {
        static $result;
        if (isset($result[$this->id])) {
            return $result[$this->id];
        }
        $adapter = $this->getAdapter();
        $result[$this->id] = $adapter->getObject($this->id);
        return $result[$this->id];
    }

    /**
     * get acl values
     * 
     * @return array
     */
    public function getAclValues ()
    {
        $aroles = $this->getAclRolesTreeArray();
        $acl = new Hosh_Manager_Db_Package_Hosh_Acl();
        $list = $acl->getList(
                array(
                        'roles' => $aroles,
                        'user' => $this->id,
                        'sysdate' => true
                ));
        return $list;
    }

    /**
     * Get roles
     * 
     * @return array
     */
    public function getAclRoles ()
    {
        static $result;
        if (isset($result[$this->id])) {
            return $result[$this->id];
        }
        $adapter = $this->getAdapter();
        $result[$this->id] = $adapter->getAclRoles($this->id);
        return $result[$this->id];
    }

    /**
     * Get roles all tree
     * 
     * @return array
     */
    public function getAclRolesTreeArray ()
    {
        $role = new Hosh_Manager_Acl_Role();
        $adapter = $role->getAdapter();
        $list = $adapter->getList();
        $arrlist = array();
        foreach ($list as $val) {
            $arrlist[$val['id']] = $val;
        }
        $aroles = array();
        $aclroles_user = $this->getAclRoles();
        foreach ($aclroles_user as $role) {
            $aroles = $this->_toUpTreeRoles($arrlist, $role['idrole'], $aroles);
        }
        return $aroles;
    }

    /**
     *
     * @param array $list            
     * @param string $id            
     * @param array $result            
     * @return array
     */
    protected function _toUpTreeRoles ($list, $id, & $result = array())
    {
        if (isset($list[$id])) {
            if (! empty($list[$id]['idparent'])) {
                $result[$id] = $id;
                $this->_toUpTreeRoles($list, $list[$id]['idparent'], $result);
            } else {
                $result[$id] = $id;
            }
        }
        return $result;
    }

    /**
     * Get Id user
     * 
     * @return string
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * Get Log history user
     * 
     * @param number $count            
     * @return array
     */
    public function getLogList ($count = 10)
    {
        $filter['iduser'] = $this->id;
        $filter['snameclass'] = array(
                'FORM',
                'EXTENSION',
                'ACL_VALUE',
                'ACL_ROLE',
                'USER',
                'CATEGORY'
        );
        $packlog = new Hosh_Manager_Db_Package_Hosh_Object_Log();
        $list = $packlog->getList($filter, $count);
        return $list;
    }
    
    /**
     * Add Log user
     * @param string $idobject
     * @param string $skind
     * @return boolean
     */
    public function addLog($idobject,$skind)
    {
       static $result;
        if (empty($this->id)){
            return false;
        }
        if (isset($result[$idobject])){
            return true;
        }
        $result[$idobject] = true;    
        $log = Hosh_Manager_Object_Log::getInstance();
        return $log->Add($idobject,$skind,$this->id);
    }    
    
}