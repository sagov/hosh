<?php

require_once 'Hosh/Form/Pattern/Abstract.php';

class Hosh_Form_Pattern_Adapter_Hoshmanager extends Hosh_Form_Pattern_Abstract
{
	public function render()
	{
		$form = $this->_form;
		$idform = $form->getIdSelf();		
		
		$result = $this->_getDataForm($idform);			
		$form_elements = $this->_getDataElements($result['id']);
		
		if (!empty($result['idclone'])){
		    $result_clone = $this->_getDataForm(null,$result['idclone']);
		    $form_elements_clone = $this->_getDataElements($result_clone['id']);
		       
		    $plugin = $this->_form->getSettingPlugin($result_clone['sname']);
		    if (is_dir($plugin['path'])){
		      $this->_form->setPlugin($plugin);
		    }
		    		    
		    $plugin = $this->_form->getSettingPlugin($idform);
		    if (is_dir($plugin['path'])){
    		    $nsSeparator = (false !== strpos($prefix, '\\'))?'\\':'_';
    		    $prefix = rtrim($plugin['prefix'], $nsSeparator);
    		    $path   = rtrim($plugin['path'], DIRECTORY_SEPARATOR);
    		    foreach (array(Hosh_Form::DECORATOR, Hosh_Form::ELEMENT, Hosh_Form::HELPER, Hosh_Form::PATTERN) as $type) {
    		        $cType        = ucfirst(strtolower($type));
    		        $pluginPath   = $path . DIRECTORY_SEPARATOR . $cType . DIRECTORY_SEPARATOR;
    		        $pluginPrefix = $prefix . $nsSeparator . $cType;
    		        $loader       = $this->_form->getPluginLoaderHosh($type);
    		        $loader->removePrefixPath($pluginPrefix);
    		    }
    		    		        
    		    $this->_form->setPlugin($plugin);
		    }
		    
		    $result = array_merge($result_clone,$result);
		    $form_elements = array_merge($form_elements_clone,$form_elements);
		    		    
		}
		
		$this->setData($result);
		foreach ($form_elements as $key=>$val_element){			
			$this->addElement($val_element['name'], $val_element);
		}		
		
		return $this;
	}

	protected function _getDataForm($sname,$id = null)
	{
	    $manager_form = new Hosh_Manager_Form();
	    if (!empty($sname)){
	        $form_data = $manager_form->getObjectByName($sname);
	    }else{
	        $form_data = $manager_form->getObject($id);
	    }
	    
	    	
	    $result = $form_data;
	    if (isset($form_data['options'])){
	        unset($result['options']);
	        $options = Zend_Json::decode($form_data['options']);
	        $result = array_merge($result,$options);
	    }
	    return $result;
	}
	
	protected function _getDataElements($idform)
	{
	    $manager_form = new Hosh_Manager_Form();
	    $form_elements = $manager_form->getElements($idform, array('snamestate'=>'NORMAL'));
	    if (!$form_elements){
	        return array();
	    }
	    $result = array();
	    foreach ($form_elements as $key=>$val_element){
	        if (isset($val_element['options'])){
	            unset($form_elements[$key]['options']);
	            $options = json_decode($val_element['options'],true);
	            $form_elements[$key] = array_merge($form_elements[$key],$options);
	        }
	        $result[$val_element['name']] = $form_elements[$key];	        
	    }
	    return $result;
	}
	
}