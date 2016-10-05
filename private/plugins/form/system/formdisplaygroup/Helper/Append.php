<?php

class HoshPluginForm_System_Formdisplaygroup_Helper_Append extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        
        $obj = new Hosh_Manager_Object($form->getData('idowner'));
        if ($obj->isLock()){
            $elements = $form->getElements();            
            foreach ($elements as $element){
                $element->setAttrib("disabled",true);
            }
        }
    }
}        