<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Value_Helper_Task_SearchUser extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		
		$request_http = new Zend_Controller_Request_Http;
		$search = $request_http->get('term');
		
		$user = new Hosh_Manager_User();
		$user_adapter = $user->getAdapter();
		$list = $user_adapter->getList(array('search'=>$search));		
		$result = array();
		foreach ($list as $key=>$val){
			$result[] = array(
					'id'=>$val['id'],
					'value'=>$val['suser'],
					'label'=>$val['suser'].' ('.$val['susername'].')',
			);
			
		}
		return $result;		
	}
}