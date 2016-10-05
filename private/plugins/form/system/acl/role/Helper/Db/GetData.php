<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Role_Helper_Db_GetData extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $updateparams = $form->getSettings('updateparams');
        $id = $updateparams['id'];
        $manager_role = new Hosh_Manager_Acl_Role();
        $adapter = $manager_role->getAdapter();
        return $adapter->getObject($id);
    }
}	