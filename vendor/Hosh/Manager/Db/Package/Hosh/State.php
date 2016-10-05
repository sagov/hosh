<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: State.php 07.04.2016 16:01:00
 */
require_once 'Hosh/Manager/Db/Table/Hosh/State.php';
/**
 * Db Package Hosh State
 *
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage  Db
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh
 *
 */
class Hosh_Manager_Db_Package_Hosh_State extends Hosh_Manager_Db_Table_Hosh_State
{

    /**
     * @param array $bind
     * @return mixed
     */
    public function register ($bind)
    {
        return parent::_register('STATE', $bind);
    }


    /**
     * @param array $filter
     * @param mixed $count
     * @param number $offset
     * @return array
     */
    public function getList ($filter = null, $count = null, $offset = 0)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        if (isset($count)){
            $select->limit($count,$offset);
        }
        return $adapter->fetchAll($select);
    }

    /**
     * @param string $filter
     * @return Zend_Db_Select
     */
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        
        $select = $adapter->select();
        $select->from(array(
                'e' => $this->info('name')
        ))
            ->join(
                array(
                        'obj' => $_table_object->info('name')
                ), 'obj.id=e.id', 
                array(
                        'idstate',
                        'dtinsert',
                        'dtupdate',
                        'sname',
                        'scaption'
                ));
        $bind = array();
        $select->bind($bind);
        return $select;
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