<?php
require_once dirName(__FILE__).'/Factory.php';

class HoshPluginForm_System_Form_Helper_Task_RemoveClone extends HoshPluginForm_System_Form_Helper_Task_Factory
{

    public function render ($options)
    {
        $form = $this->getObject();
        $idform = $form->getData('id');
                
        $form_package = new Hosh_Manager_Db_Package_Hosh_Form();
        if ($form_package->setObject($idform, array('idclone'=>null))){
            return true;
        }
    }
}	