<?php

require_once dirName(__FILE__).'/Factory.php';

class HoshPluginForm_System_Form_Helper_Task_SetClone extends HoshPluginForm_System_Form_Helper_Task_Factory
{

    public function render ($options)
    {
        $form = $this->getObject();
        $idform = $form->getData('id');
        $request_http = new Zend_Controller_Request_Http;
        $idobject = $request_http->getParam('idobject');
        
        $m_form = new Hosh_Manager_Form();
        $data = $m_form->getObject($idobject);
        
        if (empty($data['id'])){
            return false;
        }
        if (!empty($data['idclone'])){
            return false;
        }                
        $form_package = new Hosh_Manager_Db_Package_Hosh_Form();
        if ($form_package->setObject($idform, array('idclone'=>$idobject))){
            return true;
        }
    }
}	