<?php

require_once dirName(__FILE__).'/Factory.php';

class HoshPluginForm_System_Form_Helper_Task_SetChangeOrderDisplayGroup extends HoshPluginForm_System_Form_Helper_Task_Factory
{
	public function render($options){
		$form = $this->getObject();
		$request_http = new Zend_Controller_Request_Http;
		$rows = $request_http->getPost('rows');
		$idrows = explode(",", $rows);
		
		$id = $form->getData('id');
		$manager_form = new Hosh_Manager_Form();
		$formdata = $manager_form->getObject($id);
		
		$i = 10;
		$arrsort = array();
		foreach ($idrows as $val){
			$arrsort[$val] = $i;
			$i += 10;
		}
		$flag = false;
		if (isset($formdata['options'])){
			$options_data = Zend_Json::decode($formdata['options']);
			if (isset($options_data['displaygroup']['items'])){
				foreach ($options_data['displaygroup']['items'] as $key=>$val){
					if (isset($arrsort[$key])){
						$options_data['displaygroup']['items'][$key]['norder'] = $arrsort[$key];
					}
				}
				
				$bind['options'] = Zend_Json::encode($options_data);
				$package_form = new Hosh_Manager_Db_Package_Hosh_Form();
				if ($package_form->setObject($id, $bind)) {
				    $flag = true;
				}
			}
			
		}
		return $flag;
		
	}
}	