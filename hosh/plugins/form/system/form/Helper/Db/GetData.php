<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Db_GetData extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $updateparams = $form->getSettings('updateparams');
        $id = $updateparams['id'];
        
        $form_manager = new Hosh_Manager_Form();
        $form_data = $form_manager->getObject($id);
        
        if (! $form_data) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(sprintf('Object "%s" not found', $id));
            return;
        }
        
        $result_data = $form_data;
        if (isset($form_data['options'])) {
            unset($result_data['options']);
            $options = json_decode($form_data['options'], true);
            $result_data = array_merge($result_data, $options);
        }
        
        $arr_result = $result = array();
        foreach ($result_data as $key_1 => $val_1) {
            if (is_array($val_1)) {
                $this->toArray($key_1, $val_1, $arr_result);
            } else {
                $result[strtolower($key_1)] = $val_1;
            }
        }
        $result = array_merge($result, $arr_result);
        
        $_objpackagectg = new Hosh_Manager_Db_Package_Hosh_Object_Category();
        $categories = $_objpackagectg->getCategoriesObject($id);
        foreach ($categories as $val) {
            $result['idcategory'] = $val['id'];
        }
        
        return $result;
    }

    protected function toArray ($keyname, $arr, & $result = array())
    {
        if ($keyname) {
            $preff = $keyname . '_';
        } else {
            $preff = null;
        }
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $this->toArray(strtolower($preff . $key), $val, $result);
            } else {
                $result[strtolower($preff . $key)] = $val;
            }
        }
    }
}	