<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Formdisplaygroup_Helper_Db_Update extends Hosh_Form_Helper_Abstract
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

    public function render ($options)
    {
        $form = $this->getObject();
        
        $formdb = new Hosh_Manager_Db_Package_Hosh_Form();
        $pattern = $form->getPattern();
        $pattern_elements = $pattern->getElements();
        $data = $form->getDataAll();
        
        $bind = array();
        foreach ($pattern_elements as $key => $valpattern) {
            $value = null;
            if ($element = $form->getElement($key)) {
                $value = $element->getValue();
            } else 
                if (isset($data[$key])) {
                    $value = $data[$key];
                }
            if ($value === '') {
                $value = null;
            }
            if (isset($value)) {
                $arr = $this->getArrValue($key, $value);
                $bind = array_merge_recursive($bind, $arr);
            }
        }
        $updateparam = $form->getSetting('updateparams');
        $idowner = $updateparam['idowner'];
        if (empty($idowner)) {
            return false;
        }
        $manager_form = new Hosh_Manager_Form();
        $form_data = $manager_form->getObject($idowner);
        
        if (isset($form_data['options'])) {
            $options_form = Zend_Json::decode($form_data['options']);
            if (! isset($options_form['displaygroup'])) {
                $options_form['displaygroup'] = array();
            }
        } else {
            $options_form['displaygroup'] = array();
        }
        
        if ($form->isEdit()) {
            $id = $updateparam['id'];
            if (! isset($options_form['displaygroup']['items'][$id])) {
                return false;
            }
            $options_form['displaygroup']['items'][$id] = $bind;
        } else {
            if (isset($options_form['displaygroup']['items'])) {
                $count = count($options_form['displaygroup']['items']);
                $id = time();
            } else {
                $id = time();
            }
            $options_form['displaygroup']['items'][$id] = $bind;
            $form->setData('id', $id);
            $form->setData('idowner', $idowner);
        }
        
        $options_json = Zend_Json::encode($options_form);
        $bind = array();
        $bind['options'] = $options_json;
        if ($result = $formdb->setObject($idowner, $bind)) {
            return true;
        }
        return false;
    }

    protected function getArrValue ($key, $value)
    {
        $arr = array();
        $akey = explode('_', $key);
        if (count($akey) == 1) {
            $arr[$key] = $value;
        } else {
            $akeyval = null;
            foreach ($akey as $valkey) {
                $akeyval .= '[\'' . $valkey . '\']';
            }
            if (! empty($akeyval)) {
                $b = array();
                eval('$b' . $akeyval . ' = null;');
                
                foreach ($b as $keyval => $valarr) {
                    if (is_array($valarr)) {
                        foreach ($valarr as $keyval1 => $valarr1) {
                            if (is_array($valarr1)) {
                                foreach ($valarr1 as $keyval2 => $valarr2) {
                                    if (is_array($valarr2)) {
                                        foreach ($valarr2 as $keyval3 => $valarr3) {
                                            if (is_array($valarr3)) {
                                                foreach ($valarr3 as $keyval4 => $valarr4) {
                                                    if (is_array($valarr4)) {} else {
                                                        $arr[$keyval][$keyval1][$keyval2][$keyval3][$keyval4] = $value;
                                                    }
                                                }
                                            } else {
                                                $arr[$keyval][$keyval1][$keyval2][$keyval3] = $value;
                                            }
                                        }
                                    } else {
                                        $arr[$keyval][$keyval1][$keyval2] = $value;
                                    }
                                }
                            } else {
                                $arr[$keyval][$keyval1] = $value;
                            }
                        }
                    } else {
                        $arr[$keyval] = $value;
                    }
                }
            }
        }
        $result = $arr;
        return $result;
    }
}		