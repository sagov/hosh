<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Role_Helper_Task_ViewAclValues extends Hosh_Form_Helper_Abstract
{
    public function render($options){
        $form = $this->getObject();

        $request_http = new Zend_Controller_Request_Http();
        $bdeny = $request_http->getPost('bdeny');
        $filter['bdeny'] = $bdeny;

        $search = $request_http->getPost('search');
        $filter['search'] = $search;

        $xhtml = $form->getHelper('View_AclValue',array('filter'=>$filter));
        return $xhtml;
    }
}