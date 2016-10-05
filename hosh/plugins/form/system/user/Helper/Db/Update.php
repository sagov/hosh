<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_User_Helper_Db_Update extends Hosh_Form_Helper_Abstract
{
		
	public function render($options){
		$form = $this->getObject();
		
		$classdb = new Hosh_Manager_Db_Package_Hosh_User();
		$cols  = $classdb->info('cols');
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
				switch ($key)
				{
					case 'spassw':
					if (!empty($value))	$bind['spassword'] = md5($value);
						break;
				}
			}
		}
		
		unset($bind['id']);
		$flag = false;
		$adapter = $classdb->getAdapter();
		$adapter->beginTransaction();
		if ($form->isEdit()){
			if ($result = $classdb->setObject($data['id'], $bind)) {
				$flag = true;
			}
		}else{
			if ($result = $classdb->register($bind)) {				
				$form->setData('id', $result);
				$flag = true;
			}
		}
		
		if ($flag){
			if ($element = $form->getElement('idroles')){
				$idroles = $element->getValue();
				if (is_array($idroles)){
					$userroleclass = new Hosh_Manager_Db_Package_Hosh_User_Role();
					if ($userroleclass->removeUserRoles($form->getData('id'))){
						foreach ($idroles as $idrole){
							if (!$userroleclass->Add($form->getData('id'), $idrole)) {
								$flag = false;
								break;
							}
						}
					}else{
						$flag = false;						
					}
				}
			}
			
		}
		if ($flag){
			$adapter->commit();
		}else{
			$adapter->rollBack();
		}		
		return $flag;
	}
}		