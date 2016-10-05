<?php

require_once 'Hosh/Manager/Db/Package/Hosh/Form/Extension.php';

class Hosh_Manager_Db_Package_Hosh_Form_Extension extends Hosh_Manager_Db_Table_Hosh_Form_Extension
{
	
				
	public function getElements($param){		
		$_table_form_element = new Hosh_Manager_Db_Table_Hosh_Form_Element();
		$_table_form_ex = new Hosh_Manager_Db_Table_Hosh_Form_Extension();
		$_table_extension = new Hosh_Manager_Db_Table_Hosh_Extension();
		$_table_extension_kind = new Hosh_Manager_Db_Table_Hosh_Extension_Kind();
		$_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
		$_table_state = new Hosh_Manager_Db_Table_Hosh_State();
		
		$adapter = $this->getAdapter();
		
		$select = $adapter->select()
				->from(array('el'=>$_table_form_element->info('name')))
				->join(array('fex'=>$_table_form_ex->info('name')), 'fex.idowner = el.idowner',array())
				->join(array('ex'=>$_table_extension->info('name')), 'ex.id = fex.idextension',array('sname_extension'=>'sname'))
				->join(array('exkind'=>$_table_extension_kind->info('name')), 'exkind.id = ex.idkind',array('snamekind'=>'sname'))
				->join(array('obj'=>$_table_object->info('name')), 'obj.id=el.id',array('idstate','dtinsert','dtupdate'))
				->join(array('state'=>$_table_object->info('name')), 'state.id=obj.idstate', array('snamestate'=>'sname','sstate'=>'scaption'))
				->order(array('el.idowner','el.norder'))				
		;
		$bind = array();
		if (isset($param['snamekind']))	{
			$select->where('exkind.sname = :snamekind');
			$bind['snamekind'] = $param['snamekind'];
		}
		if (isset($param['sname'])){
			if (is_array($param['sname'])){
				$select->where('ex.sname IN (?)',$param['sname']);
			}else{
				$select->where('ex.sname  = :sname');
				$bind['sname'] = $param['sname'];
			}
		}
		
		$select->bind($bind);	
		$result = $adapter->fetchAll($select);
		return $result;
	}

	public function removeExtension($id)
	{
	    $sql = 'DELETE FROM `'.$this->info('name').'`  WHERE idextension = :id';
	    $stmt = $this->getAdapter()->query($sql,array('id'=>$id));
	    return $stmt;
	}
	
}