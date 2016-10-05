<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_Translate extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        
        $valuetranslate = $options['value'];
        if (! empty($valuetranslate)) {
            $content = explode(',', $valuetranslate);
            $form->addTranslation($content);
        }
        return;
    }
}		