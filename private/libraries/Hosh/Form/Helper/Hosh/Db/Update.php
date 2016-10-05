<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_Db_Update extends Hosh_Form_Helper_Abstract
{
    
    
    public function render($options){
        return false;
    }
		
	protected function _update(Hosh_Manager_Db_Table_Abstract $package)
	{
		$form = $this->getObject();			
		$pattern = $form->getPattern();
		$pattern_elements = $pattern->getElements();
		$data = $form->getDataAll();
		
		$bind = array();
		foreach ($pattern_elements as $key=>$valpattern)
		{
			$value = null;
					
			if ($element = $form->getElement($key) and ($valpattern->get('type') != 'value')){			    
				$value = $element->getValue();
			}else if (isset($data[$key])){
				$value = $data[$key];
			}
			if ($value === '') {
			    $value = null;
			}
			$bind[$key] = $value;			
		}
		
		unset($bind['id']);
		$adapter = $package->getAdapter();
		$adapter->beginTransaction();
		if ($form->isEdit()){	
		    $cols  = $package->info('cols');
		    $bind_pack = array_intersect_key($bind,array_flip($cols));		    
			if ($result = $package->setObject($data['id'], $bind_pack)) {
			    if (!$this->_appendUpdate()){
			        $adapter->rollBack();
			        return false;
			    }
			    $adapter->commit();
			    return true;
			}			
		}else{		    		    
		    if ($result = $package->register($bind)) {
			    $form->setData('id', $result);
			    $adapter->commit();
			    return true;
			}
			
		}
		$adapter->rollBack();
		return false;
	}
	
	protected function _appendUpdate()
	{
	    $form = $this->getObject();
	    $data = $form->getDataAll();	    
	    $bind_object = array();
	    if ($element = $form->getElement('scaption')){
	        $bind_object['scaption'] = $element->getValue();
	    }
	    if ($element = $form->getElement('sname')){
	        $bind_object['sname'] = $element->getValue();
	        if (empty($bind_object['sname'])){
	            $bind_object['sname'] = $data['id'];
	        }
	    }
	    if (count($bind_object)>0){
	        $package_object = new Hosh_Manager_Db_Package_Hosh_Object();
	        if (!$package_object->setObject($data['id'], $bind_object)){	            
	            return false;
	        }
	    }
	    return true; 
	}
}		