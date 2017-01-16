<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Value.php 07.04.2016  10:59:46
 */
require_once 'Hosh/Manager/Db/Table/Hosh/Acl/Value.php';

/**
 * Db Package Hosh_Acl_Role
 *
 * @category Hosh
 * @package Hosh_Manager
 * @subpackage Db
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_Db_Package_Hosh_Acl_Value extends Hosh_Manager_Db_Table_Hosh_Acl_Value
{

    /**
     * @param string $idself
     * @return array
     */
    public function getObject ($idself)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect();
        $select->where('aclvalue.id = :id')->bind(
                array(
                        'id' => $idself
                ));
        $result[$idself] = $adapter->fetchRow($select);
        return $result[$idself];
    }

    /**
     * @param array $bind
     * @return mixed
     */
    public function register ($bind)
    {
        return parent::_register('ACL_VALUE', $bind);
    }

    /**
     * @param array $filter
     * @return Zend_Db_Select
     */
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();

        
        $select = $adapter->select()
            ->from(
                array(
                        'aclvalue' => $this->info('name')
                ))
            ->join(
                array(
                        'obj' => $_table_object->info('name')
                ), 'obj.id=aclvalue.id', 
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
                ));
        
        $bind = array();
        if (! empty($filter['sname'])) {
            $select->where(
                    'obj.sname LIKE ' . $adapter->quote($filter['sname'] . '%'));
        }


        if (! empty($filter['idowner'])) {
            $_table_acl = new Hosh_Manager_Db_Table_Hosh_Acl();

            $select_acl = $_table_acl->getAdapter()->select();
            $select_acl->from(array('acl'=>$_table_acl->info('name')),'idvalue');
            $select_acl->where('acl.idowner in ('.$adapter->quote($filter['idowner']).')');

            if (! empty($filter['skind'])) {
                $select_acl->where('acl.skind = :skind');
                $bind['skind'] = $filter['skind'];
            }

            if (! empty($filter['bdeny'])) {
                $select_acl->where('acl.bdeny = :bdeny');
                $bind['bdeny'] = $filter['bdeny'];
            }

            $select->where('obj.id in ('.$select_acl->__toString().')');
        }


        $select->bind($bind);
        
        return $select;
    }

    /**
     * @param array $filter
     * @param integer $count
     * @param integer $offset
     * @return array
     */
    public function getList ($filter = null, $count = null, $offset = null)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        if (isset($count)) {
            $select->limit($count, $offset);
        }
        //echo $select->assemble();
        $result = $adapter->fetchAll($select);
        return $result;
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
        $expr = new Zend_Db_Expr('count(aclvalue.ID) as count');
        $select->columns($expr);
        $count = $adapter->fetchOne($select);
        return (int)($count);
    }

    /**
     * @param sring $idself
     * @return array
     */
    public function getAcl ($idself)
    {
        $_table_acl = new Hosh_Manager_Db_Table_Hosh_Acl();
        $adapter = $this->getAdapter();
        
        $select = $adapter->select()
            ->from(
                array(
                        'acl' => $_table_acl->info('name')
                ))
            ->where('acl.idvalue = :idself')
            ->bind(array(
                'idself' => $idself
        ));
        return $adapter->fetchAll($select);
    }

    /**
     * @param string $idself
     * @return Zend_Db_Statement_Interface
     */
    public function removeAcl ($idself)
    {
        $_table_acl = new Hosh_Manager_Db_Table_Hosh_Acl();
        $bind['idvalue'] = $idself;
        $sql = 'DELETE FROM `' . $_table_acl->info('name') . '`
					WHERE idvalue = :idvalue';
        
        $stmt = $this->getAdapter()->query($sql, $bind);
        return $stmt;
    }

    /**
     * @param string $idself
     * @param string $idowner
     * @param string $skind
     * @return Zend_Db_Statement_Interface
     */
    public function removeAclByOwner ($idself, $idowner, $skind)
    {
        $_table_acl = new Hosh_Manager_Db_Table_Hosh_Acl();
        $bind['idvalue'] = $idself;
        $bind['idowner'] = $idowner;
        $bind['skind'] = $skind;
        $sql = 'DELETE FROM `' . $_table_acl->info('name') . '`
					WHERE idvalue = :idvalue and idowner = :idowner and skind = :skind';
        
        $stmt = $this->getAdapter()->query($sql, $bind);
        return $stmt;
    }
}