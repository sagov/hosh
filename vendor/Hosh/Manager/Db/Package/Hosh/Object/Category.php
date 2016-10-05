<?php

require_once 'Hosh/Manager/Db/Table/Hosh/Object/Category.php';

class Hosh_Manager_Db_Package_Hosh_Object_Category extends Hosh_Manager_Db_Table_Hosh_Object_Category
{
    public function removeObject($id)
    {
        $sql = 'DELETE FROM `'.$this->info('name').'`  WHERE idobject = :id';
        $stmt = $this->getAdapter()->query($sql,array('id'=>$id));
        return $stmt;
    }
    
    public function Add($idobject,$idcategory){
        $data['idobject'] = $idobject;
        $data['idcategory'] = $idcategory;
        if (!$this->insert($data)){
            return false;
        }
        return true;
    }
    
    public function getCategoriesObject($idobject)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect(array('idobject'=>$idobject));
        return $adapter->fetchAll($select);        
    }
    
    protected function _getSelect($param = null){
    
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_state = new Hosh_Manager_Db_Table_Hosh_State();
        $_category = new Hosh_Manager_Db_Table_Hosh_Category();
    
        $select = $adapter->select();
        $select->from(array('e'=>$_category->info('name')))
        ->join(array('ectg'=>$this->info('name')), 'e.id=ectg.idcategory',array())
        ->join(array('obj'=>$_table_object->info('name')), 'obj.id=e.id',array('idstate','dtinsert','dtupdate'))
        ->join(array('state'=>$_table_object->info('name')), 'state.id=obj.idstate', array('snamestate'=>'sname','sstate'=>'scaption'));
        $bind = array();
    
        if (isset($param['idobject'])){
            $select->where('ectg.idobject = :idobject');
            $bind['idobject'] = $param['idobject'];
        }
    
        $select->bind($bind);
        return $select;
    }
}
