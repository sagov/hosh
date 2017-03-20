<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Extension_Helper_Db_GetData extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $updateparams = $form->getSettings('updateparams');
        $id = $updateparams['id'];
        $package = new Hosh_Manager_Extension();
        $object = $package->getObject($id);
        if (empty($object['id'])){
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(sprintf('Object "%s" not found', $id));
            return;
        }

        $result_data = $object;
        if (isset($object['options'])) {
            unset($result_data['options']);
            $options = json_decode($object['options'], true);
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

        if (in_array(strtolower($result['snamekind']),
                array(
                        'form_helper',
                        'form_element',
                        'list_helper',
                        'list_element',
                ))) {
            $_formtable = new Hosh_Manager_Db_Table_Hosh_Form_Extension();
            $adapter = $_formtable->getAdapter();
            $select = $adapter->select();
            $select->from($_formtable->info('name'), 'idowner')
                ->where('idextension = :id')
                ->bind(array(
                    'id' => $id
            ));
            $row = $adapter->fetchRow($select);
            if (! empty($row['idowner'])) {
                $result['idform'] = $row['idowner'];
            }
        }
        
        $_extctgpackage = new Hosh_Manager_Db_Package_Hosh_Object_Category();
        $categories = $_extctgpackage->getCategoriesObject($id);
        
        foreach ($categories as $val){
            $result['idcategory'][] = $val['id'];
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