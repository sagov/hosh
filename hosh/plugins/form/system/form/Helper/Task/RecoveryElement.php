<?php

require_once dirName(__FILE__).'/Factory.php';

class HoshPluginForm_System_Form_Helper_Task_RecoveryElement extends HoshPluginForm_System_Form_Helper_Task_Factory
{
	public function render($options){
		$form = $this->getObject();
		$request_http = new Zend_Controller_Request_Http;
		$idelement = $request_http->getPost('idelement');
		
		if (empty($idelement)) return false;
		
		$id = $form->getData('id');
		$package_formelement = new Hosh_Manager_Db_Package_Hosh_Form_Element();
		$element = $package_formelement->getObject($idelement);
		if ($element['idowner'] !== $id) {
		    return false;
		}		
		return ($package_formelement->setStateName($idelement, 'Normal')) ? true : false;
	}
}		