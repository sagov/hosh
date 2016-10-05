<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Formelement_Helper_Db_Update extends Hosh_Form_Helper_Abstract
{
    
    function __construct($object){
        parent::__construct($object);
    
        $form = $this->getObject();        
    
        $id = $form->getData('idowner');
        $hobject = new Hosh_Manager_Object($id);
        if ($hobject->isLock()){
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Объект заблокирован');
            return false;
        }
    }
		
    public function render($options){
		$form = $this->getObject();
		
		$formdb = new Hosh_Manager_Db_Package_Hosh_Form_Element();
		$cols  = $formdb->info('cols');
		$pattern = $form->getPattern();
		$pattern_elements = $pattern->getElements();
		
		$data = $form->getDataAll();
		$bind = array();
		foreach ($pattern_elements as $key=>$valpattern)
		{
			$value = null;
			if ($element = $form->getElement($key)){
				$value = $element->getValue();
			}else if (isset($data[$key])){
				$value = $data[$key];
			}
			if ($value === '') $value = null;
			
			if (in_array($key,$cols)){
				$bind[$key] = $value;
			}else{
				if (isset($value)){
					$arr = $this->getArrValue($key,$value);
					$bind = array_merge_recursive($bind,$arr);
				}			
			}
		}
		if (isset($bind['options'])){
			$bind['options'] = Zend_Json::encode($bind['options']);
		}
		unset($bind['id']);
		if ($form->isEdit()){			
			$id = $form->getData('id');			
			unset($bind['idowner']);			
			if ($formdb->setObject($id, $bind))	return true;			
		}else{
			
			if ($result = $formdb->register($bind)) {
				$form->setData('id', $result);
				return true;
			}
		}
		
		return false;
		
	}
	
	 protected function getArrValue($key,$value){
	 	$arr = array();
	 		$akey = explode('_',$key);
			if (count($akey) == 1){
					$arr[$key] = $value;
			}else{				
				$akeyval = null;				
				foreach ($akey as $valkey){
					$akeyval .= '[\''.$valkey.'\']';										
				}
				if (!empty($akeyval)){
				$b = array();
				eval('$b'.$akeyval.' = null;');				
				
				foreach ($b as $keyval=>$valarr){
					if (is_array($valarr)){
						foreach ($valarr as $keyval1=>$valarr1){
							if (is_array($valarr1)){
								foreach ($valarr1 as $keyval2=>$valarr2){
									if (is_array($valarr2)){
										foreach ($valarr2 as $keyval3=>$valarr3){
											if (is_array($valarr3)){
												foreach ($valarr3 as $keyval4=>$valarr4){
													if (is_array($valarr4)){
												
													}else{
														$arr[$keyval][$keyval1][$keyval2][$keyval3][$keyval4] = $value;
													}
												}
											}else{
												$arr[$keyval][$keyval1][$keyval2][$keyval3] = $value;
											}
										}
									}else{
										$arr[$keyval][$keyval1][$keyval2] = $value;
									}
								}
							}else{
								$arr[$keyval][$keyval1] = $value;
							}
						}
					}else{
						$arr[$keyval] = $value;
					}
				}
				
				}
			}
			$result['options'] = $arr;
			return $result;
	}
}		