<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_List_Helper_Append extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        if (! $form->isEdit()) {
            return;
        }
        
        $id = $form->getData('id');
        $obj = new Hosh_Manager_Object($id);
        if ($obj->isLock()){
            $elements = $form->getElements();
            foreach ($elements as $element){
                $element->setAttrib("disabled",true);
            }
        }
        
    }
}