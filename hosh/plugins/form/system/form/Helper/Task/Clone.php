<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Task_Clone extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $idform = $form->getData('id');
        
        $m_form = new Hosh_Manager_Form();
        $data = $m_form->getObject($idform);
        if (!empty($data['idclone'])){
            return false;
        }        
        $form_package = new Hosh_Manager_Db_Package_Hosh_Form();
        $data['idclone'] = $data['id'];
        unset($data['id']);
        unset($data['sname']);
        unset($data['options']);
        $adapter = $form_package->getAdapter();
        $adapter->beginTransaction();
        if (! $result = $form_package->register($data)) {
            $adapter->rollBack();
            return false;
        }        
        $adapter->commit();
        return $result;
    }
}	