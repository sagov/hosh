<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_AddPatternElementsType extends Hosh_Form_Helper_Abstract
{
	public function render($options){		
		
		if (empty($options['value'])) return;
		
		$sname = $options['value'];
				
		$form = $this->getObject();
		$pattern = $form->getPattern();
				
		$m_extension = new Hosh_Manager_Extension();
		$formex = $m_extension->getList(array('sname'=>$sname,'snamekind'=>'Form_Element')); 
		
		$idaform = array();
		foreach ($formex as $val){
			if (isset($val['idowner'])){
				$idaform[$val['idowner']] = $val['idowner'];				
			}
		}
		
		$form_elements = array();
		if (count($idaform)>0){
		    $package_form = new Hosh_Manager_Form();
		    foreach ($idaform as $idform){		        
		        $formdata = $package_form->getObject($idform);
		        $form->addTranslation('form/'.strtolower($formdata['sname']));
		        if (!empty($formdata['idclone'])){
		            $formdata_clone = $package_form->getObject($formdata['idclone']);
		            $form->addTranslation('form/'.strtolower($formdata_clone['sname']));
		            $idaform[$formdata['idclone']] = $formdata['idclone'];
		        }
		    }
		    $form_elements = $package_form->getElements($idaform);
		}		
		
		if (count($form_elements) == 0) return;
		
		$list = array();
		foreach ($form_elements as $key=>$val_element){
			if (isset($val_element['options'])){
				unset($form_elements[$key]['options']);
				if (isset($val_element['options'])){
					$options = Zend_Json::decode($val_element['options']);
					if (is_array($options)) $form_elements[$key] = array_merge($form_elements[$key],$options);
				}
			}			
			$list[$val_element['name']] = $form_elements[$key];
				
			$pattern->addElement($val_element['name'], $list[$val_element['name']]);			
		}		
		
		return;
		
	}
}