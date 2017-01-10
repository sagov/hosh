<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Category.php 06.04.2016 19:24:33
 */
require_once 'Hosh/Manager/Db/Table/Hosh/Category.php';
/**
 * Package Hosh Category
 * 
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage Db
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Db_Package_Hosh_Category extends Hosh_Manager_Db_Table_Hosh_Category
{

    /**
     * Register new category
     * @param array $bind
     * @return mixed
     */
    public function register ($bind)
    {
        return parent::_register('CATEGORY', $bind);
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
        
        
        $select = $adapter->select();
        $select->from(array(
                'e' => $this->info('name')
        ))
            ->join(array(
                'objkind' => $_table_object->info('name')
        ), 'e.idkind=objkind.id', array(
                'skindname' => 'sname'
        ))
            ->join(array(
                'obj' => $_table_object->info('name')
        ), 'obj.id=e.id', 
                array(
                        'idstate',
                        'dtinsert',
                        'dtupdate',
                        'sname',
                        'scaption',
                        'bsystem'
                ))
            ->join(array(
                'state' => $_table_object->info('name')
        ), 'state.id=obj.idstate', 
                array(
                        'snamestate' => 'sname',
                        'sstate' => 'scaption'
                ));
        $bind = array();
        
        if (isset($filter['skindname'])) {
            $select->where('objkind.sname = :skindname');
            $bind['skindname'] = $filter['skindname'];
        }
        
        if (isset($filter['id'])) {
            $select->where('e.id = :id');
            $bind['id'] = $filter['id'];
        }
        
        $select->bind($bind);
        return $select;
    }

    /**
     * List category
     * @param array $filter
     * @return array
     */
    public function getList ($filter = null)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        $list = $adapter->fetchAll($select);
        return $list;
    }

    /**
     * Data category
     * @param string $idself
     * @return mixed
     */
    public function getObject ($idself)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect(array('id'=>$idself));        
        return $adapter->fetchRow($select);
    }
}