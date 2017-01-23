<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Role_Helper_Task_RemoveAcl extends Hosh_Form_Helper_Abstract
{


    public function render ($options)
    {

        $form = $this->getObject();
        $idowner = $form->getData('id');
        if (empty($idowner)) {
            return false;
        }

        $user = Hosh_Manager_User_Auth::getInstance();
        if (!$user->isAllowed('HOSH_SYSTEM_ACL_EDIT')){
            return false;
        }

        $request_http = new Zend_Controller_Request_Http();
        $idvalue = $request_http->getPost('idaclvalue');
        $acl_value = new Hosh_Manager_Db_Package_Hosh_Acl_Value();
        if (! $acl_value->removeAclByOwner($idvalue, $idowner, Hosh_Manager_Db_Package_Hosh_Acl::ACL_SKIND_ROLE)) {
            return false;
        }

        $xhtml = $form->getHelper('View_AclValue');
        return array('result'=>true,'list'=>$xhtml);
    }
}