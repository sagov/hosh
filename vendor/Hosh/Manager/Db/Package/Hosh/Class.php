<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Class.php 07.04.2016 16:01:00
 */
require_once 'Hosh/Manager/Db/Table/Hosh/Class.php';
/**
 * Description of file_name
 * 
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage  Db
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Db_Package_Hosh_Class extends Hosh_Manager_Db_Table_Hosh_Class
{

    /**
     * Register new class
     * @param array $bind
     * @return mixed
     */
    public function register ($bind)
    {
        return parent::_register('CLASS', $bind);
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
        if (! empty($filter['sname'])) {
            $select->where('obj.sname = :sname');
            $bind['sname'] = $filter['sname'];
        }
        $select->bind($bind);
        return $select;
    }

    /**
     * @param array $filter
     * @return array
     */
    public function getList ($filter = null,$count = null, $offset = 0)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        if (isset($count)){            
            $select->limit($count,$offset);
        }
        return $adapter->fetchAll($select);
    }

    /**
     * @param array $filter
     * @return integer
     */
    public function getTotal ($filter = null)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        $select->reset('order')
            ->reset('group')
            ->reset('limitcount')
            ->reset('limitoffset')
            ->reset('columns');
        $expr = new Zend_Db_Expr('count(e.ID) as count');
        $select->columns($expr);
        $count = $adapter->fetchOne($select);
        return $count;
    }
}