<?php

require_once 'Hosh/Manager/Db/Table/Hosh/Extension/Kind.php';

class Hosh_Manager_Db_Package_Hosh_Extension_Kind extends Hosh_Manager_Db_Table_Hosh_Extension_Kind
{
	public function getList($filter = null){		
		$adapter = $this->getAdapter();
		$select = $this->_getSelect($filter);		
		return $adapter->fetchAll($select);		
	}
	
	protected function _getSelect($filter = null){
	
		$adapter = $this->getAdapter();
		$_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
		$_table_state = new Hosh_Manager_Db_Table_Hosh_State();
		$_table_extkind = new Hosh_Manager_Db_Table_Hosh_Extension_Kind();
	
		$select = $adapter->select()
		->from(array('exkind'=>$this->info('name')))
		->join(array('obj'=>$_table_object->info('name')), 'obj.id=exkind.id',array('idstate','dtinsert','dtupdate','sname','scaption'))
		->join(array('state'=>$_table_object->info('name')), 'state.id=obj.idstate', array('snamestate'=>'sname','sstate'=>'scaption'))
		;
		
		$bind = array();
		if (!empty($filter['sname'])){
		    $select->where('obj.sname = :sname');
		    $bind['sname'] = $filter['sname'];
		}
		$select->bind($bind);
	
		return $select;
	}
}