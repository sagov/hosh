<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_DisplayGroup extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		
		if (!isset($options['items'])){
			return;
		}
		$list = $options['items'];
		
		$form = $this->getObject();
		
		$arr_elements = array();
		$pattern = $form->getPattern();
		$list_elements = $pattern->getElements();
		
		if (!empty($list_elements)){
			foreach ($list_elements as $val){
				$data = $val->getData();
				if (!empty($data['displaygroup'])){
					$element = $form->getElement($val->get('name'));
					if ($element) $arr_elements[$val->get('displaygroup')][] = $val->get('name');
				}
			}
		}
		
		foreach ($list as $key=>$val){
				
			if (!empty($arr_elements[$key])){
				$options = array();
				if (isset($val['label'])) $options['Legend'] = $val['label'];
				if (empty($val['sname'])) $val['sname'] = $key;
				//$options['DisableLoadDefaultDecorators'] = true;
				if (isset($val['norder'])) $options['Order'] = $val['norder'];
				$form->addDisplayGroup($arr_elements[$key], $val['sname'],$options);		
		
				/* $val['decorator']['name'] = $key;						
				if (!empty($val['decorator']['helper'])){
					$form->addHelper($val['decorator']['helper'],$val['decorator']);
				}else{
					$form->addHelper('Decorator_DisplayGroup',$val['decorator']);
				} */
			}
		}
		return;
	}
}	