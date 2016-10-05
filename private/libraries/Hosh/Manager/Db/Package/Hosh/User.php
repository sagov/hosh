<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: User.php 06.03.2016 15:24:57
 */
require_once 'Hosh/Manager/Db/Table/Hosh/User.php';
/**
 * Package Hosh Manager
 *
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage  Db
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh
 *
 */
class Hosh_Manager_Db_Package_Hosh_User extends Hosh_Manager_Db_Table_Hosh_User
{

    /**
     * @param string $idself
     * @return array
     */
    public function getObject ($idself)
    {        
        $adapter = $this->getAdapter();
        $select = $this->_getSelect();
        $select->where('user.id = :id')->bind(array(
                'id' => $idself
        ));
        return $adapter->fetchRow($select);        
    }

    public function register ($bind)
    {
        return parent::_register('USER', $bind);        
    }

    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_state = new Hosh_Manager_Db_Table_Hosh_State();
        
        $select = $adapter->select()
            ->from(array(
                'user' => $this->info('name')
        ))
            ->join(array(
                'obj' => $_table_object->info('name')
        ), 'obj.id=user.id', array(
                'idstate',
                'dtinsert',
                'dtupdate'
        ))
            ->join(array(
                'state' => $_table_object->info('name')
        ), 'state.id=obj.idstate', 
                array(
                        'snamestate' => 'sname',
                        'sstate' => 'scaption'
                ));
        
        $bind = array();
        if (isset($filter['susername'])) {
            $select->where('user.susername = :susername');
            $bind['susername'] = $filter['susername'];
        }
        if (isset($filter['search'])) {
            $select->where(
                    'user.suser like :search OR user.susername like :search');
            $bind['search'] = $filter['search'] . '%';
        }
        $select->bind($bind);
        
        return $select;
    }

    
    public function getList ($filter = null, $count = null, $offset = 0)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        if (isset($count)) {
            if (! isset($offset) or $offset < 0) {
                $offset = 0;
            }
            $select->limit($count, $offset);
        }
        return $adapter->fetchAll($select);
    }
    
    public function getTotal($filter = null)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        $select->reset('order')
            ->reset('group')
            ->reset('limitcount')
            ->reset('limitoffset')
            ->reset('columns');
        $expr = new Zend_Db_Expr('count(user.ID) as count');
        $select->columns($expr);
        return (int)($adapter->fetchOne($select));
    }
}