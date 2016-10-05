<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Append extends Hosh_Form_Helper_Abstract
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
        
        $snamekind = $form->getData('snamekind');
        $pattern = $form->getPattern();
        $layout = $pattern->get('layout');
        
        $m_form = new Hosh_Manager_Form();
        $kinds = $m_form->getKinds();
        if (isset($kinds[$snamekind])) {
            if (isset($kinds[$snamekind]['layout'])) {
                $layout['update']['name'] = $kinds[$snamekind]['layout'];
                $pattern->set('layout', $layout);
            }
        }      
        
    }
}