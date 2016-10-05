<?php

class HoshPluginForm_System_Formelement_Helper_Element_ListDisplayGroup extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$translate = $form->getTranslator();
		$idowner = $form->getData('idowner');
		$manager_form = new Hosh_Manager_Form();		
		$form_data = $manager_form->getObject($idowner);
		if (isset($form_data['options'])){
			$options = Zend_Json::decode($form_data['options']);			
		}
		$result = array();
		$result[''] = '-- --';
		if (isset($options['displaygroup']['items'])){
			
			$options_data_displaygroup_order = array();
			foreach ($options['displaygroup']['items'] as $key=>$val){
				if (!isset($val['norder'])) $val['norder'] = 0;
				$options_data_displaygroup_order[$key] = (int)($val['norder']);				
			}
			
			asort($options_data_displaygroup_order,SORT_NUMERIC);
			foreach ($options_data_displaygroup_order as $key=>$val){
				$group = $options['displaygroup']['items'][$key];
				if (empty($group['label'])) $group['label'] = sprintf($translate->_('SYS_FORM_DISPLAYGROUP_LABEL_UNDEFINED'),$key);
				$result[$key] = $group['label'];
			}
		}
		return $result;
	}
}		