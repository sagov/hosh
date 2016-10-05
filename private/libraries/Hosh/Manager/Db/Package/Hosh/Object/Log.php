<?php
require_once 'Hosh/Manager/Db/Table/Hosh/Object/Log.php';

class Hosh_Manager_Db_Package_Hosh_Object_Log extends Hosh_Manager_Db_Table_Hosh_Object_Log
{

    public function getList ($filter, $count = 10, $offset = 0)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        $select->order('log.dtinner desc');
        $select->limit($count,$offset);
        $select->group('log.idobject');
        $result = $adapter->fetchAll($select);
        return $result;
    }

    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_class = new Hosh_Manager_Db_Table_Hosh_Class();
        
        $select = $adapter->select();
        $select->from(array(
                'log' => $this->info('name')
        ))
            ->join(array(
                'obj' => $_table_object->info('name')
        ), 'obj.id=log.idobject', array(
                'idclass','sname','scaption'
        ))
            ->join(array(
                'class' => $_table_object->info('name')
        ), 'class.id=obj.idclass', array(
                'snameclass' => 'sname'
        ));
        $bind = array();    
        if (isset($filter['iduser'])){
           $select->where('log.iduser = :iduser');
           $bind['iduser'] = $filter['iduser']; 
        } 
        if (isset($filter['snameclass'])){
            $select->where('class.sname in ('.$adapter->quote($filter['snameclass']).')');
        }
           
        $select->bind($bind);    
        return $select;
    }
}