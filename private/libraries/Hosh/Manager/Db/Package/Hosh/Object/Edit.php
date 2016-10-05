<?php
require_once 'Hosh/Manager/Db/Table/Hosh/Object/Edit.php';

class Hosh_Manager_Db_Package_Hosh_Object_Edit extends Hosh_Manager_Db_Table_Hosh_Object_Edit
{
        
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();        
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();        
    
        $select = $adapter->select();
        $select->from(array(
                'edit' => $this->info('name')
        ));       
        $bind = array();
        if (isset($filter['idobject'])){
            $select->where('edit.idobject = :idobject');
            $bind['idobject'] = $filter['idobject'];
        }
        if (isset($filter['iduser'])){
            $select->where('edit.iduser = :iduser');
            $bind['iduser'] = $filter['iduser'];
        }         
        $select->bind($bind);
        return $select;
    }
    
    public function getListByObject($idobject)
    {
        $adapter = $this->getAdapter();
        $filter = array('idobject'=>$idobject);
        $select = $this->_getSelect($filter);
        $result = $adapter->fetchAll($select);
        return $result;
    }
    
    public function Register($bind)
    {
        $adapter = $this->getAdapter();
        $sql = 'REPLACE INTO '.$adapter->quoteTableAs($this->info('name')).' (idobject,iduser) VALUES (:idobject,:iduser)';        
        $stmt = $adapter->query($sql,$bind);
        return $stmt;
    }
    
    public function removeItem($idobject,$iduser)
    {
        $bind['idobject'] = $idobject;
        $bind['iduser'] = $iduser;
        $sql = 'DELETE FROM `' . $this->info('name') . '` 
					WHERE idobject = :idobject and iduser = :iduser';
        $stmt = $this->getAdapter()->query($sql, $bind);
        return $stmt;
    }
    
    public function remove($idobject)
    {
        $bind['idobject'] = $idobject;        
        $sql = 'DELETE FROM `' . $this->info('name') . '`
					WHERE idobject = :idobject';
        $stmt = $this->getAdapter()->query($sql, $bind);
        return $stmt;
    }
}