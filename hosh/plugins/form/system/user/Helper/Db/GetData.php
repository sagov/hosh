<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_User_Helper_Db_GetData extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$updateparams = $form->getSettings('updateparams');
		$id = $updateparams['id'];
		
		$usermanager = new Hosh_Manager_User($id);		
		$data = $usermanager->getData();
		$listroles = $usermanager->getAclRoles();
		if ($listroles){
			foreach ($listroles as $val){
				$data['idroles'][] = $val['idrole'];
			}	
		}		
		
		return $data;
	}
}	