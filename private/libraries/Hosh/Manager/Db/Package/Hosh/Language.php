<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Language.php 06.03.2016 15:24:57
 */
require_once 'Hosh/Manager/Db/Table/Hosh/Language.php';

/**
 * Package Hosh Manager
 *
 * @category Hosh
 * @package Hosh_Manager
 * @subpackage Db
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_Db_Package_Hosh_Language extends Hosh_Manager_Db_Table_Hosh_Language
{

    /**
     * Register new form
     *
     * @param array $bind            
     * @return boolean|mixed
     */
    public function register ($bind)
    {
        return parent::_register('LANGUAGE', $bind);
    }

    /**
     *
     * @param array $filter            
     * @return Zend_Db_Select
     */
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_state = new Hosh_Manager_Db_Table_Hosh_State();
        
        $select = $adapter->select();
        $select->from(
                array(
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
        
        if (isset($filter['snamestate'])){
            $select->where('state.sname=:snamestate');
            $bind['snamestate'] = $filter['snamestate'];
        }
        
        if (isset($filter['bpublic'])){
            $select->where('e.bpublic=:bpublic');
            $bind['bpublic'] = $filter['bpublic'];
        }
        
        $select->bind($bind);
        return $select;
    }

    /**
     * List language
     * 
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
}