<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Role.php 07.04.2016  10:59:46
 */
require_once 'Hosh/Manager/Db/Table/Hosh/Acl/Role.php';
/**
 * Db Package Hosh_Acl_Role
 * 
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage Db
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Db_Package_Hosh_Acl_Role extends Hosh_Manager_Db_Table_Hosh_Acl_Role
{

    /**
     * @param string $idself
     * @return mixed
     */
    public function getObject ($idself)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect(array(
                'id' => $idself
        ));
        return $adapter->fetchRow($select);
    }

    /**
     * Register new Acl Role
     * @param array $bind
     * @return mixed
     */
    public function register ($bind)
    {
        return parent::_register('ACL_ROLE', $bind);
    }

    /**
     * @param array $filter
     * @return Zend_Db_Select
     */
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_state = new Hosh_Manager_Db_Table_Hosh_State();
        
        $select = $adapter->select()
            ->from(
                array(
                        'aclrole' => $this->info('name')
                ))
            ->join(
                array(
                        'obj' => $_table_object->info('name')
                ), 'obj.id=aclrole.id', 
                array(
                        'idstate',
                        'dtinsert',
                        'dtupdate',
                        'scaption',
                        'sname'
                ))
            ->join(
                array(
                        'state' => $_table_object->info('name')
                ), 'state.id=obj.idstate', 
                array(
                        'snamestate' => 'sname',
                        'sstate' => 'scaption'
                ));
        
        $bind = array();
        if (! empty($filter['sname'])) {
            $select->where('obj.sname = :sname');
            $bind['sname'] = $filter['sname'];
        }
        if (! empty($filter['id'])) {
            $select->where('aclrole.id = :id');
            $bind['id'] = $filter['id'];
        }
        $select->bind($bind);
        
        return $select;
    }

    /**
     * List Acl Roles
     * @param array $filter
     * @return array
     */
    public function getList ($filter = null)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        return $adapter->fetchAll($select);
    }
}