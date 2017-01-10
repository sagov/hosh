<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Category_Helper_GetParentList extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$updateparams = $form->getSettings('updateparams');
		$idselected = $updateparams['id'];


        $filter = array();
        $filter['skindname'] = $form->getData('skindname');
		$manager_category = new Hosh_Manager_Category();		
		$list = $manager_category->getList($filter);
		if (count($list) == 0){
		    return array();
		}
		$applist = Hosh_Application_List::getInstance();
		$treelist = $applist->toTree($list);
		
		
		
		$result = array();
		$result[''] = '-- --';
		$array_disable = array(); $valselected = array();
		$disable = false;
		
		foreach ($treelist as $val){
				
			$xhtml_level = $applist->getLevelCaption($val['level']);
			if ($disable){
				if ((int)($val['level'])<=(int)($valselected['level'])) $disable = false;
			}
			if (($val['id'] == $idselected)or($disable)) {
				$array_disable[] = $val['id'];
				if ($val['id'] == $idselected) {
				    $valselected = $val;
				}
				$disable = true;
			}
			$result[$val['id']] = $xhtml_level.$val['scaption'].' ('.$val['sname'].')';
		}
		
		if (count($array_disable)>0){
			$element = $form->getElement($options['element_name']);
			$element->setOptions(array('disable'=>$array_disable));
		}
		
		return $result;
	}
	
}	