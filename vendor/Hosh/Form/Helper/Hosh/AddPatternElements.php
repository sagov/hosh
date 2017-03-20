<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_AddPatternElements extends Hosh_Form_Helper_Abstract
{
	public function render($options){

		$sname = $fname = array();
		foreach ($options['helpers'] as $val){
			$sname[$val['value']] = $val['value']; 
			$fname[$val['value']][$val['name']] = $val;
		}
		
		if (count($sname) == 0) {
		    return;
		}
		
		$form = $this->getObject();
		$pattern = $form->getPattern();
		$pattern_elements = $pattern->getElements();

        $idowner = $options['idowner'];
        $snamekind = null;
        if (!empty($idowner)){
            $hobject = new Hosh_Manager_Object($idowner);
            $classname = $hobject->getClassName();

            switch ($classname)
            {
                case 'LIST':
                    $snamekind = 'List_Helper';
                    break;

                case 'FORM':
                    $snamekind = 'Form_Helper';
                    break;

                default:
                    $snamekind = null;
                    break;

            }
        }


		$m_extension = new Hosh_Manager_Extension();
		$formhelpers = $m_extension->getList(array('sname'=>$sname,'snamekind'=>$snamekind));


		$idaform = $ahelper = array();
		foreach ($formhelpers as $val){
			if (isset($val['idowner'])){
				$idaform[$val['idowner']] = $val['idowner'];
				$ahelper[$val['idowner']][$val['sname']] = $val;
			}
		}
		
		$form_elements = array();
		if (count($idaform)>0){
		    $manager_form = new Hosh_Manager_Form();
            $form_data = $manager_form->getList(array('id'=>$idaform));
			$form_elements = $manager_form->getElements($idaform);			
		}
		
		if (count($form_elements) == 0) {
		    return;
		}

		foreach ($form_data as $val){
            $form->addTranslation('form/'.strtolower($val['id']));
            if (isset($val['options'])){
                $options = Zend_Json::decode($val['options']);
                if (!empty($options['translate']['helper'])){
                    $form->getHelper($options['translate']['helper'],$options['translate']);
                }
            }
        }
		
				
		$list = array();
		foreach ($form_elements as $key=>$val_element){
			if (isset($val_element['options'])){
				unset($form_elements[$key]['options']);
				if (isset($val_element['options'])){
					$options = Zend_Json::decode($val_element['options']);
					if (is_array($options)) $form_elements[$key] = array_merge($form_elements[$key],$options);
				}
			}
			if (isset($ahelper[$val_element['idowner']])){
				foreach ($ahelper[$val_element['idowner']] as $key_helper=>$fhelper_val){
					if (isset($fname[$key_helper])){
						foreach ($fname[$key_helper] as $key_fname=>$fname_val){
							$form_elements[$key]['name'] = str_replace('_helper','',$fname[$key_helper][$key_fname]['name']).'_'.$val_element['name'];
							$form_elements[$key]['parent'] = $key_fname;
							$list[$key_fname][$form_elements[$key]['name']] = $form_elements[$key];
						}
						
					}					
					
				}
			}
			
		}

		$i = 0;
		foreach ($pattern_elements as $key=>$row){			
			$row->set('order', $i);
			++$i;
			if (isset($list[$key])){
				
				foreach ($list[$key] as $keyelement=>$val_element){
					$val_element['order'] = $i;
					$pattern->addElement($keyelement, $val_element);
					++$i;
				}
			}
			
		}		
		
		
		return;
		
	}
}