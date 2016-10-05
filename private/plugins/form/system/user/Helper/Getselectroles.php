<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_User_Helper_Getselectroles extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$translate = $form->getTranslator();
		
		$role = new Hosh_Manager_Acl_Role();
		$adapter = $role->getAdapter();
		$list = $adapter->getList();
		$applist = Hosh_Application_List::getInstance();		
		$treelist = $applist->toTree($list);		
		$result = array();				
		foreach ($treelist as $val){				
			$xhtml_level = $applist->getLevelCaption($val['level']);
			$result[$val['id']] = $xhtml_level.$translate->_($val['scaption']).' ('.$val['sname'].')';
		}		
		
		return $result;
	}	
}	