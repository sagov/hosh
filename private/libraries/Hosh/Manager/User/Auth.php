<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Auth.php 07.04.2016 14:21:26
 */
require_once 'Hosh/Manager/User.php';

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
class Hosh_Manager_User_Auth extends Hosh_Manager_User
{

    /**
     * Pref User Role
     * 
     * @var unknown
     */
    const PREF_USER_ROLE = 'user_';

    /**
     *
     * @var unknown
     */
    protected static $_instance = null;

    public function __construct ()
    {}

    /**
     *
     * @return Hosh_Manager_User_Auth
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            $adapter = self::$_instance->getAdapter();
            $user = $adapter->getAuthUser();
            if (isset($user->id)) {                
                self::$_instance->id = $user->id;
                self::$_instance->_InstanceAcl();
            }
        }
        return self::$_instance;
    }

    /**
     * Instance Acl
     * 
     * @return Hosh_Manager_User_Auth
     */
    protected function _InstanceAcl ()
    {
        $data = $this->getData();
        if (!empty($data['bsuper'])){
            return $this;
        }
        $acl = Hosh_Manager_Acl::getInstance();
        $acl->AddRoles();
        $aclvalues = $this->getAclValues();
        
        $role = self::PREF_USER_ROLE . $this->id;
        if (! $acl->hasRole($role)) {
            $acl->addRole(new Zend_Acl_Role($role));
        }
        $list = array();
        foreach ($aclvalues as $val) {
            $list[$val['bdeny']][] = $val['sname'];
        }
        
        foreach ($list as $bdeny => $val) {
            if (! empty($bdeny)) {
                $acl->deny($role, null, $val);
            } else {
                $acl->allow($role, null, $val);
            }
        }
        return $this;
    }

    /**
     * Check Acl Value
     * 
     * @param string $value            
     * @return boolean
     */
    public function isAllowed ($value)
    {        
        if (empty($this->id)) {
            return false;
        }
        $data = $this->getData();
        if (!empty($data['bsuper'])){
            return true;
        }
        $acl = Hosh_Manager_Acl::getInstance();
        return $acl->isAllowed(self::PREF_USER_ROLE . $this->id, null, $value) ? true : false;
    }

    /**
     * 
     * @return boolean
     */
    public function isExist ()
    {        
        return (! empty($this->id)) ? true : false;
    }
}