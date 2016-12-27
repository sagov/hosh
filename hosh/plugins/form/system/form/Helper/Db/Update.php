<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Db_Update extends Hosh_Form_Helper_Abstract
{

    protected $_exc_elements = array(
            'idstate',
            'idcategory',
            'scaption',
            'sname'
    );
    
    function __construct($object){
        parent::__construct($object);
        
        $form = $this->getObject();
        if (!$form->isEdit()){
            return;
        }
        
        $id = $form->getData('id');
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
        $cols = $formdb->info('cols');
        $pattern = $form->getPattern();
        $pattern_elements = $pattern->getElements();
        
        $data = $form->getDataAll();
        
        if (isset($data['id'])) {
            $formdata = $formdb->getObject($data['id']);
            if (isset($formdata['options'])) {
                $options_data = Zend_Json::decode($formdata['options']);
            }
        }
        
        $bind = array();
        foreach ($pattern_elements as $key => $valpattern) {
            $value = null;
            if (! in_array($key, $this->_exc_elements)) {
                if ($element = $form->getElement($key)) {
                    if (! $element instanceof Zend_Form_Element_Submit) {
                        $value = $element->getValue();
                    }
                } else 
                    if (isset($data[$key])) {
                        $value = $data[$key];
                    }
                if ($value === '') {
                    $value = null;
                }
                
                if (in_array($key, $cols)) {
                    $bind[$key] = $value;
                } else {
                    if (isset($value)) {
                        $arr = $this->getArrValue($key, $value);
                        $bind = array_merge_recursive($bind, $arr);
                    }
                }
            }
        }
        if (isset($bind['options'])) {
            if (isset($options_data['displaygroup']['items'])) {
                $bind['options']['displaygroup']['items'] = $options_data['displaygroup']['items'];
            }
            $bind['options'] = Zend_Json::encode($bind['options']);
        }
        
        unset($bind['id']);
        if ($form->isEdit()) {
            $adapter = $formdb->getAdapter();
            $adapter->beginTransaction();
            // update form
            if (! $formdb->setObject($data['id'], $bind)) {
                $adapter->rollBack();
                return false;
            }
            // update state object
            if ($element = $form->getElement('idstate')) {
                $idstate = $element->getValue();
                if (! empty($idstate) and ($data['idstate'] !== $idstate)) {
                    $objectdb = new Hosh_Manager_Db_Package_Hosh_Object();
                    if (! $objectdb->setState($data['id'], $idstate)) {
                        $adapter->rollBack();
                        return false;
                    }
                }
            }
            
            if ($element = $form->getElement('idcategory')) {
                $idcategory = $element->getValue();
                $objpackagectg = new Hosh_Manager_Db_Package_Hosh_Object_Category();
                $objpackagectg->removeObject($data['id']);
                if (! empty($idcategory)) {
                    if (! $objpackagectg->Add($data['id'], $idcategory)) {
                        $adapter->rollBack();
                        return false;
                    }
                }
            }
            
            $bind_object = array();
            if ($element = $form->getElement('scaption')) {
                $bind_object['scaption'] = $element->getValue();
            }
            if ($element = $form->getElement('sname')) {
                $bind_object['sname'] = $element->getValue();
                if (empty($bind_object['sname'])){
                    $bind_object['sname'] = $data['id'];
                }
            }
            if (count($bind_object) > 0) {
                $package_object = new Hosh_Manager_Db_Package_Hosh_Object();
                if (! $package_object->setObject($data['id'], $bind_object)) {
                    $adapter->rollBack();
                    return false;
                }
            }
            
            $adapter->commit();
            return true;
        } else {            
            if ($element = $form->getElement('scaption')) {
                $bind['scaption'] = $element->getValue();
            }
            if ($element = $form->getElement('sname')) {
                $bind['sname'] = $element->getValue();
            }
            if ($result = $formdb->register($bind)) {
                $form->setData('id', $result);
                return true;
            }
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
        $result['options'] = $arr;
        return $result;
    }
}	