<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Value_Helper_Task_AddAcl extends Hosh_Form_Helper_Abstract
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
        $bdeny = $request_http->getPost('bdeny');
        $dtfrom = $request_http->getPost('dtfrom');
        $dttill = $request_http->getPost('dttill');
        
        $acl_value = new Hosh_Manager_Db_Package_Hosh_Acl_Value();
        $acl = new Hosh_Manager_Db_Package_Hosh_Acl();
        if (! $acl_value->removeAclByOwner($idvalue, $idowner, $this->skind)) {
            return false;
        }
        if (! $acl->Add($idvalue, $idowner, $bdeny, $this->skind, $dtfrom, 
                $dttill)) {
            return false;
        }
        return true;
    }
}