<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Acl.php 07.04.2016 14:52:16
 */
require_once 'Zend/Acl.php';
/**
 * Acl
 *
 * @category Hosh
 * @package Hosh_Manager
 * @subpackage Acl
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_Acl extends Zend_Acl
{

    /**
     *
     * @var Hosh_Manager_Acl
     */
    protected static $_instance = null;

    /**
     *
     * @return Hosh_Manager_Acl
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    /**
     * Add roles
     *
     * @return Hosh_Manager_Acl
     */
    public function AddRoles ()
    {
        $role = new Hosh_Manager_Acl_Role();
        $adapter = $role->getAdapter();
        $list = $adapter->getList();
        $applist = Hosh_Application_List::getInstance();
        $listtree = $applist->toTree($list);
        $parentlist = array();
        foreach ($list as $key => $val) {
            if (isset($val['sname'])) {
                if ($this->hasRole($val['sname'])) {
                    $this->removeRole($val['sname']);
                }
                $this->addRole(new Zend_Acl_Role($val['sname']));
                if (! empty($val['idparent']) and
                         (! empty($listtree[$val['idparent']]))) {
                    $parentlist[$val['sname']][] = $listtree[$val['idparent']]['sname'];
                }
            }
        }
        
        foreach ($parentlist as $key => $val) {
            if ($this->hasRole($key)) {
                $this->removeRole($key);
            }
            $this->addRole(new Zend_Acl_Role($key), $val);
        }
        
        return $this;
    }
}