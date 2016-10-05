<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Language_Helper_Prepend extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        
        $value_sname = $form->getData('sname');
        if ($value_sname == Hosh_Translate::LOCALE_DEFAULT) {
            $pattern = $form->getPattern();
            $pattern_sname = $pattern->getElement('sname');
            $pattern_sname->set('bpublic', 2);
            
            $pattern_bpublic = $pattern->getElement('bpublic');
            $pattern_bpublic->set('bpublic', 2);
        }
        return $this;
    }
}	