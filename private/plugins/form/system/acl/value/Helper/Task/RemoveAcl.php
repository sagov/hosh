<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Value_Helper_Task_RemoveAcl extends Hosh_Form_Helper_Abstract
{

    protected $skind;

    public function render ($options)
    {
        if (empty($this->skind)) {
            return false;
        }
        
        $form = $this->getObject();
        $idvalue = $form->getData('id');
        if (empty($idvalue)) {
            return false;
        }
        $request_http = new Zend_Controller_Request_Http();
        $idowner = $request_http->getPost('idowner');
        $acl_value = new Hosh_Manager_Db_Package_Hosh_Acl_Value();
        if (! $acl_value->removeAclByOwner($idvalue, $idowner, $this->skind)) {
            return false;
        }
        
        return true;
    }
}