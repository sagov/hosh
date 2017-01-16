<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Acl.php 08.04.2016 12:17:39
 */
require_once 'Hosh/Manager/Db/Table/Hosh/Acl.php';
/**
 * Db Package Hosh_Acl
 * 
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage  Db
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Db_Package_Hosh_Acl extends Hosh_Manager_Db_Table_Hosh_Acl
{

    /**
     * @var unknown
     */
    const ACL_SKIND_ROLE = 'r';

    /**
     * @var unknown
     */
    const ACL_SKIND_USER = 'u';

    /**
     * @param string $idvalue
     * @param string $idowner
     * @param integer $bdeny
     * @param string $skind
     * @param string $dtfrom
     * @param string $dttill
     * @return boolean
     */
    public function Add ($idvalue, $idowner, $bdeny, $skind, $dtfrom = null, 
            $dttill = null)
    {
        $data['idvalue'] = $idvalue;
        $data['idowner'] = $idowner;
        $data['bdeny'] = $bdeny;
        $data['skind'] = $skind;
        $data['dtfrom'] = $dtfrom;
        $data['dttill'] = $dttill;
        if (empty($data['dtfrom'])) {
            $data['dtfrom'] = null;
        }
        if (empty($data['dttill'])) {
            $data['dttill'] = null;
        }
        if (! $this->insert($data)) {
            return false;
        }
        return true;
    }

    /**
     * @param array $filter
     * @return array
     */
    public function getList ($filter = null)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        return $adapter->fetchAll($select);
    }

    /**
     * @param array $filter
     * @return Zend_Db_Select
     */
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_acl_value = new Hosh_Manager_Db_Table_Hosh_Acl_Value();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();

        
        $select = $adapter->select()
            ->from(array(
                'acl' => $this->info('name')
        ))
            ->join(array(
                'aclvalue' => $_acl_value->info('name')
        ), 'acl.idvalue=aclvalue.id', array(
                'public'
        ))
            ->join(array(
                'obj' => $_table_object->info('name')
        ), 'obj.id=aclvalue.id', 
                array(
                        'idstate',
                        'dtinsert',
                        'dtupdate',
                        'sname',
                        'scaption'
                ))
            ->join(array(
                'state' => $_table_object->info('name')
        ), 'state.id=obj.idstate', 
                array(
                        'snamestate' => 'sname',
                        'sstate' => 'scaption'
                ));
        
        $bind = $where_or = array();
        if (isset($filter['roles'])and(count($filter['roles'])>0)) {
            $where_or[] = '(acl.idowner in (' . $adapter->quote(
                    $filter['roles']) . ') and acl.skind=' .
                     $adapter->quote(self::ACL_SKIND_ROLE) . ')';
        }
        if (isset($filter['user'])) {
            $where_or[] = '(acl.idowner = :user and acl.skind=' .
                     $adapter->quote(self::ACL_SKIND_USER) . ')';
            $bind['user'] = $filter['user'];
        }
        
        if (isset($filter['sysdate'])) {
            $select->where(' acl.dtfrom < NOW() or acl.dtfrom is null');
            $select->where(' acl.dttill > NOW() or acl.dttill is null');
        }

        if (isset($filter['snamestate'])) {
            $select->where('state.sname = :snamestate');
            $bind['snamestate'] = $filter['snamestate'];
        }

        if (isset($filter['bdeny'])) {
            $select->where('acl.bdeny = :bdeny');
            $bind['bdeny'] = $filter['bdeny'];
        }
        
        if (count($where_or) > 0) {
            $select->where(implode(' OR ', $where_or));
        }
        $select->bind($bind);
        return $select;
    }
}