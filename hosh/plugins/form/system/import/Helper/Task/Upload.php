<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Import_Helper_Task_Upload extends Hosh_Form_Helper_Abstract
{

    protected $_cols_form_ext = array(
            'snamestate',
            'sstate',
            'snamekind'
    );

    public function render ($options)
    {
        $file = $_FILES['file'];
        switch ($file['type']) {
            case 'text/xml':
                return $this->_importxml($file);
                break;
            default:
                break;
        }
        return false;
    }
    protected function _importxml ($file)
    {
        $xml = new Zend_Config_Xml($file['tmp_name']);
        $arr_data = $xml->toArray();
        
        switch ($arr_data['type'])
        {
            case 'hosh_form':
                return $this->_importxmlForm($arr_data);
                break;
            default:
                break;    
        }
        return false;
    }

    protected function _importxmlForm ($arr_data)
    {
        
        $form_data = $arr_data['data'];
        $element_data = array();
        if (isset($arr_data['elements']['item'])) {
            $element_data = $arr_data['elements']['item'];
            if (! isset($element_data[0])) {
                $element_data = array(
                        $element_data
                );
            }
        }
       
        if (isset($form_data['displaygroup']['items']['item'])) {
            if (! isset($form_data['displaygroup']['items']['item'][0])) {
                $form_data['displaygroup']['items']['item'] = array(
                        $form_data['displaygroup']['items']['item']
                );
            }
            foreach ($form_data['displaygroup']['items']['item'] as $val) {
                $idgroup = $val['id'];
                unset($val['id']);
                $form_data['displaygroup']['items'][$idgroup] = $val;
            }
            unset($form_data['displaygroup']['items']['item']);
        } else 
            if (isset($form_data['displaygroup'])) {
                unset($form_data['displaygroup']);
            }
        $hform = new Hosh_Manager_Form();
        $_data = $hform->getObjectByName($form_data['sname']);
        if (! empty($_data['id'])) {
            $form_data['id'] = $_data['id'];
        } else {
            unset($form_data['id']);
        }
        $h_state = new Hosh_Manager_State();
        
        if (! empty($form_data['snamestate'])) {
            $form_data['idstate'] = $h_state->NameToId($form_data['snamestate']);
        }
        
        if (! empty($form_data['snamekind'])) {
            $formkinds = $hform->getKinds();
            if (isset($formkinds[$form_data['snamekind']])) {
                $form_data['idkind'] = $formkinds[$form_data['snamekind']]['id'];
            }
        }
        
        $table_form = new Hosh_Manager_Db_Package_Hosh_Form();
        $cols_form = $table_form->info('cols');
        $table_element = new Hosh_Manager_Db_Package_Hosh_Form_Element();
        $cols_form_element = $table_element->info('cols');
        $table_object = new Hosh_Manager_Db_Package_Hosh_Object();
        $cols_object = $table_object->info('cols');
        $cols = array_merge($cols_form, $cols_object);
        $cols = array_merge($cols, $this->_cols_form_ext);
        $bind_form_data = array();
        foreach ($cols as $val) {
            if (isset($form_data[$val])) {
                $bind_form_data[$val] = $form_data[$val];
                unset($form_data[$val]);
            }
        }
        $bind_form_data['options'] = Zend_Json::encode($form_data);
        
        $adapter = $table_form->getAdapter();
        $adapter->beginTransaction();
        if (! empty($bind_form_data['id'])) {
            $bind_form = array_intersect_key($bind_form_data, 
                    array_flip($cols_form));
            if (isset($bind_form['id'])) {
                unset($bind_form['id']);
            }
            if (! $result = $table_form->setObject($bind_form_data['id'], 
                    $bind_form)) {
                $adapter->rollBack();
                return false;
            }
            $bind_object = array_intersect_key($bind_form_data, 
                    array_flip($cols_object));
            if (! $result = $table_object->setObject($bind_form_data['id'], 
                    $bind_object)) {
                $adapter->rollBack();
                return false;
            }
            $idobject = $bind_form_data['id'];
            if (! $hform->removeElements($idobject)) {
                $adapter->rollBack();
                return false;
            }
        } else {
            if (! $idobject = $table_form->register($bind_form_data)) {
                $adapter->rollBack();
                return false;
            }
        }
        
        if (empty($idobject)) {
            $adapter->rollBack();
            return false;
        }
        
        $cols = array_merge($cols_form_element, $cols_object);
        $cols = array_merge($cols, $this->_cols_form_ext);
        
        foreach ($element_data as $element) {
            $element['idowner'] = $idobject;
            $element['idstate'] = $h_state->NameToId($element['snamestate']);
            if (isset($element['id'])) {
                unset($element['id']);
            }
            $bind_form_element_data = array();
            foreach ($cols as $val) {
                if (isset($element[$val]) and ($val != 'options')) {
                    $bind_form_element_data[$val] = $element[$val];
                    unset($element[$val]);
                }
            }
            
            $bind_form_element_data['options'] = Zend_Json::encode($element);
            
            if (! $idelement = $table_element->register($bind_form_element_data)) {
                $adapter->rollBack();
                return false;
            }
        }
        
        if (! empty($idobject)) {
            $adapter->Commit();
            return array(
                    'idobject' => $idobject
            );
        }
        
        return false;
    }
}    