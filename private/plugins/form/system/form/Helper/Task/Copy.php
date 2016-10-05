<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Task_Copy extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $idform = $form->getData('id');
        
        $m_form = new Hosh_Manager_Form();
        $data = $m_form->getObject($idform);
        $elements = $m_form->getElements($idform);
        
        $form_package = new Hosh_Manager_Db_Package_Hosh_Form();
        unset($data['id']);
        unset($data['sname']);
        $adapter = $form_package->getAdapter();
        $adapter->beginTransaction();
        if (! $result = $form_package->register($data)) {
            $adapter->rollBack();
            return false;
        }
        $form_element_package = new Hosh_Manager_Db_Package_Hosh_Form_Element();
        foreach ($elements as $element){
            unset($element['id']);
            unset($element['sname']);
            $element['idowner'] = $result;
            if (!$form_element_package->register($element)){
                $adapter->rollBack();
                return false;
            }
        }
        
        $adapter->commit();
        return $result;
    }
}	