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
        if (in_array(strtolower($object['snamekind']), 
                array(
                        'form_helper',
                        'form_element'
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
                $object['idform'] = $row['idowner'];
            }
        }
        
        $_extctgpackage = new Hosh_Manager_Db_Package_Hosh_Object_Category();
        $categories = $_extctgpackage->getCategoriesObject($id);
        
        foreach ($categories as $val){
            $object['idcategory'][] = $val['id'];
        }
        
        return $object;
    }
}	