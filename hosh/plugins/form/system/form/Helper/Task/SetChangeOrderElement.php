<?php

require_once dirName(__FILE__).'/Factory.php';

class HoshPluginForm_System_Form_Helper_Task_SetChangeOrderElement extends HoshPluginForm_System_Form_Helper_Task_Factory
{
	public function render($options){
		$form = $this->getObject();
		$request_http = new Zend_Controller_Request_Http;
		$rows = $request_http->getPost('rows');
		$idrows = explode(",", $rows);
		
		$id = $form->getData('id');
		$manager_form = new Hosh_Manager_Form();
		$package_form_element = new Hosh_Manager_Db_Package_Hosh_Form_Element();
		$list = $manager_form->getElements($id);
		
		$i = 10;
		$arrsort = array();
		foreach ($idrows as $val){
			$arrsort[$val] = $i;
			$i += 10; 
		}
		
		$adapter = $package_form_element->getAdapter();
		$adapter->beginTransaction();
		$flag = true;
		foreach ($list as $val){
			if (isset($arrsort[$val['id']])){
				$bind['norder'] = $arrsort[$val['id']];
				if (!$package_form_element->setObject($val['id'], $bind)){
					$flag = false;
					break;
				}
			}
		}
		if ($flag) $adapter->commit(); else $adapter->rollBack();
		return $flag;
	}
}	