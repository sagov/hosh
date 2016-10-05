<?php
require_once 'Hosh/Form/Helper/Hosh/Db/Update.php';

class HoshPluginForm_System_Extension_Helper_Db_Update extends Hosh_Form_Helper_Hosh_Db_Update
{

    public function render ($options)
    {
        $package = new Hosh_Manager_Db_Package_Hosh_Extension();
        return $this->_update($package);
    }

    protected function _appendUpdate ()
    {
        $form = $this->getObject();
        $data = $form->getDataAll();
        $bind_object = array();
        if ($element = $form->getElement('scaption')) {
            $bind_object['scaption'] = $element->getValue();
        }
        if (count($bind_object) > 0) {
            $package_object = new Hosh_Manager_Db_Package_Hosh_Object();
            if (! $package_object->setObject($data['id'], $bind_object)) {
                return false;
            }
        }
        
        if ($element = $form->getElement('idform')) {
            $idform = $element->getValue();
            
            if ($idform !== $data['idform']) {
                $extpackage = new Hosh_Manager_Db_Package_Hosh_Form_Extension();
                if (! $extpackage->removeExtension($data['id'])) {
                    return false;
                }
                if (! empty($idform)) {
                    if (! $extpackage->insert(
                            array(
                                    'idowner' => $idform,
                                    'idextension' => $data['id']
                            ))) {
                        return false;
                    }
                }
            }
        }
        
        if ($element = $form->getElement('idcategory')) {
            $idcategories = $element->getValue();
            if (is_array($idcategories)) {
                $extctgpackage = new Hosh_Manager_Db_Package_Hosh_Object_Category();
                $extctgpackage->removeObject($data['id']);
                foreach ($idcategories as $idcategory) {
                    if (! $extctgpackage->Add($form->getData('id'), $idcategory)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}		