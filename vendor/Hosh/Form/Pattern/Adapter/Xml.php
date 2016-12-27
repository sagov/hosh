<?php

require_once 'Hosh/Form/Pattern/Abstract.php';

class Hosh_Form_Pattern_Adapter_Xml extends Hosh_Form_Pattern_Abstract
{
    public function render()
    {
        $form = $this->_form;
        $idform = $form->getIdSelf();
               
        $arr_data = $this->_getDataFile($idform);
        $result = $arr_data['data'];        
        $form_elements = $arr_data['elements'];
        
        if (!empty($result['idclone'])){
            $arr_data_clone = $this->_getDataFile($result['idclone']);
            $result_clone = $arr_data_clone['data'];
            $form_elements_clone = $arr_data_clone['elements'];
             
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
        
            $result = array_merge($result,$result_clone);
            $form_elements = array_merge($form_elements,$form_elements_clone);
        }

        $this->setData($result);        
        foreach ($form_elements as $key=>$val_element){
            $this->addElement($val_element['name'], $form_elements[$key]);
        }        
        return $this;
    }
    
    protected function _getDataFile($idform)
    {
        static $result;
        if (isset($result[$idform])){
            return $result[$idform];
        }
        $form = $this->_form;
        $settings = $form->getSetting('pattern');
        if (isset($settings['data'])){
            $path_file = $settings['data'];
        }else{
            $file = $idform.'.xml';
            $config = Hosh_Config::getInstance();
            $configform = $config->get('form');
            $path = $configform->get('pattern')->path_xml;
            $path_file = $path . $file;
        }
        
        if (!file_exists($path_file)){
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(
                    sprintf('Form  "%s" not exists',
                            $idform));
        }
        $xml = new Zend_Config_Xml($path_file);
        $arr_data = $xml->toArray();
        
        $result_element = $result_displaygroup = array();
        if (isset($arr_data['data']['displaygroup']['items']['item'])){            
            $form_displaygroup = $arr_data['data']['displaygroup']['items']['item'];
            if (!isset($form_displaygroup[0])){
            	$form_displaygroup = array($form_displaygroup);            	
            }
            foreach ($form_displaygroup as $val){
            	$result_displaygroup[$val['id']] = $val;
            }
            $arr_data['data']['displaygroup']['items'] = $result_displaygroup;
        }        
        if (isset($arr_data['elements']['item'])){      
            $form_elements = $arr_data['elements']['item'];
            if (!isset($form_elements[0])){
                $form_elements = array($form_elements);
            }        
            foreach ($form_elements as $val){
                if (isset($val['norder']) and $val['norder'] == ''){
                    $val['norder'] = NULL;
                }
                $result_element[$val['name']] = $val;
            }
        }
        $result[$idform] = $arr_data;
        $result[$idform]['elements'] = $result_element;
        return $result[$idform];
    }
}