<?php

class HoshPluginForm_System_Auth_Helper_Append extends Hosh_Form_Helper_Abstract
{
    public function render($options)
    {
        $form = $this->getObject();
        $elements = $form->getElements();
        foreach ($elements as $element){
            $label = $element->getDecorator('Label');
            if ($label){
                $label->setOptSuffix('');
            }
        }
        return;
    }
}