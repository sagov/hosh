<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Form.php 06.03.2016 15:24:57
 */
require_once 'Hosh/Manager/Db/Table/Hosh/Form.php';

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
class Hosh_Manager_Db_Package_Hosh_Form extends Hosh_Manager_Db_Table_Hosh_Form
{

    /**
     * Register new form
     *
     * @param array $bind            
     * @return boolean|mixed
     */
    public function register ($bind)
    {
        return parent::_register('FORM', $bind);
    }


    /**
     * Get data form
     *
     * @param string $idself            
     * @return mixed
     */
    public function getObject ($idself)
    {
        if (empty($idself)) {
            return false;
        }
        $adapter = $this->getAdapter();
        $select = $this->_getSelect(
                array(
                        'id' => $idself
                ));
        return $adapter->fetchRow($select);
    }

    /**
     * Get data form
     *
     * @param string $sname            
     * @return mixed
     */
    public function getObjectByName ($sname)
    {
        if (empty($sname)) {
            return false;
        }
        $adapter = $this->getAdapter();
        $select = $this->_getSelect(
                array(
                        'sname' => $sname
                ));
        return $adapter->fetchRow($select);
    }

    /**
     *
     * @param array $param            
     * @return Zend_Db_Select
     */
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_state = new Hosh_Manager_Db_Table_Hosh_State();
        $_table_fkind = new Hosh_Manager_Db_Table_Hosh_Form_Kind();
        
        $select = $adapter->select()
            ->from(
                array(
                        'form' => $this->info('name')
                ))
            ->join(
                array(
                        'obj' => $_table_object->info('name')
                ), 'obj.id=form.id', 
                array(
                        'idstate',
                        'dtinsert',
                        'dtupdate',
                        'sname',
                        'scaption',
                        'bsystem'
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
                        'fkind' => $_table_object->info('name')
                ), 'fkind.id=form.idkind', 
                array(
                        'snamekind' => 'sname'
                ));
        
        $bind = array();
        if (! empty($filter['id'])) {
            $select->where('form.id = :id');
            $bind['id'] = $filter['id'];
        }
        
        if (! empty($filter['sname'])) {
            $select->where('obj.sname = :sname');
            $bind['sname'] = $filter['sname'];
        }
        
        if (! empty($filter['idkind'])) {
            if (is_array($filter['idkind'])) {
                $select->where('form.idkind IN (?)', $filter['idkind']);
            } else {
                $select->where('form.idkind = :idkind');
                $bind['idkind'] = $filter['idkind'];
            }
        }
        
        if (isset($filter['snamekind'])) {
            $select->where('fkind.sname = :snamekind');
            $bind['snamekind'] = $filter['snamekind'];
        }
        
        $select->bind($bind);
        return $select;
    }

    /**
     * Get list form
     *
     * @param array $filter            
     * @param number $count            
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
     * Get total count forms
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
        $expr = new Zend_Db_Expr('count(form.ID) as count');
        $select->columns($expr);
        return $adapter->fetchOne($select);
    }
    
        
    /**
     * @param string $idowner
     * @return Zend_Db_Statement_Interface
     */
    public function removeElements($idowner)
    {
        if (empty($idowner)) {
            return false;
        }
        $adapter = $this->getAdapter();        
        $_table_form_element = new Hosh_Manager_Db_Table_Hosh_Form_Element();
        $bind['idowner'] = $idowner;        
        $sql = 'DELETE FROM '.$adapter->quoteTableAs($_table_form_element->info('name')).'
                WHERE idowner = :idowner ';
        $stmt = $adapter->query($sql, $bind);
        return $stmt;
    }
}