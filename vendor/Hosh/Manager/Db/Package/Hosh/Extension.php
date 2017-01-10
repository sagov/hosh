<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Extension.php 06.03.2016 15:24:57
 */
require_once 'Hosh/Manager/Db/Table/Hosh/Extension.php';

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
class Hosh_Manager_Db_Package_Hosh_Extension extends Hosh_Manager_Db_Table_Hosh_Extension
{

    /**
     *
     * @param array $bind            
     * @return mixed
     */
    public function register ($bind)
    {
        return parent::_register('EXTENSION', $bind);
    }

    /**
     *
     * @param string $idself            
     * @return mixed
     */
    public function getObject ($idself)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect();
        $select->where('ex.id = :id')->bind(
                array(
                        'id' => $idself
                ));
        $result = $adapter->fetchRow($select);
        
        return $result;
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
        
        $select = $adapter->select()
            ->from(
                array(
                        'ex' => $this->info('name')
                ))
            ->join(
                array(
                        'obj' => $_table_object->info('name')
                ), 'obj.id=ex.id', 
                array(
                        'idstate',
                        'dtinsert',
                        'dtupdate',
                        'scaption',
                        'bsystem',
                ))
            ->join(
                array(
                        'state' => $_table_object->info('name')
                ), 'state.id=obj.idstate', 
                array(
                        'snamestate' => 'sname',
                        'sstate' => 'scaption'
                ))
            ->join(
                array(
                        'extkind' => $_table_object->info('name')
                ), 'extkind.id=ex.idkind', 
                array(
                        'snamekind' => 'sname'
                ));
        
        $bind = array();
        if (isset($filter['sname'])) {
            if (is_array($filter['sname'])) {
                $select->where('ex.sname IN (?)', $filter['sname']);
            } else {
                $select->where('ex.sname = :sname');
                $bind['sname'] = $filter['sname'];
            }
        }
        
        if (isset($filter['snamekind'])) {
            $select->where('extkind.sname = :snamekind');
            $bind['snamekind'] = $filter['snamekind'];
            
            if (in_array(strtolower($filter['snamekind']), 
                    array(
                            'form_helper',
                            'form_element'
                    ))) {
                $_table_formtable = new Hosh_Manager_Db_Table_Hosh_Form_Extension();
                $select->joinLeft(
                        array(
                                'fex' => $_table_formtable->info('name')
                        ), 'fex.idextension=ex.id', 
                        array(
                                'idowner'
                        ));
            }
        }
        
        if (isset($filter['iscategory'])) {
            $_packcategory = new Hosh_Manager_Db_Package_Hosh_Object_Category();
            $select->joinLeft(
                    array(
                            'ctg' => $_packcategory->info('name')
                    ), 'ctg.idobject=ex.id', 
                    array(
                            'idcategory'
                    ));
        }
        
        $select->bind($bind);
        
        return $select;
    }

    /**
     *
     * @param array $filter            
     * @param string $count            
     * @param number $offset            
     * @return array
     */
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

    /**
     * Get total count
     *
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
        $expr = new Zend_Db_Expr('count(ex.ID) as count');
        $select->columns($expr);
        return $adapter->fetchOne($select);
    }
}