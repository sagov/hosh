<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Validate_Regex extends Hosh_Form_Helper_Abstract
{
    
    protected $breakChainOnFailure = false;
    protected $regex_value;
    protected $regex_js;
    protected $bcheckjs = false;
    
    public function render($options)
    {
        
        if (isset($options['regex'])) {
            $regex = $options['regex'];
        }
        if (isset($regex['value'])) {
            $this->regex_value = $this->regex_js = $regex['value'];
        }
        if (isset($options['bcheckjs'])) {
            $this->bcheckjs = (boolean)($options['bcheckjs']);
        }
        if (isset($regex['js'])) {
            $this->regex_js = $regex['js'];
        }
        
        if (isset($options['element_name'])) {
            $element_name = $options['element_name'];    
        }
        
        if (empty($element_name)) {
            return ;
        }
                
        $form = $this->getObject();
        $idform = $form->getId();
        $element = $form->getElement($element_name);
        if (!$element){
            return;
        }
        $id_element = $element->getId();
        if (!empty($this->regex_value)){
            $element->addValidator('Regex',$this->breakChainOnFailure,$this->regex_value);
        }        
        $this->setScript($id_element);        
        return ;
    }
    
    protected function setScript($id_element)
    {
        static $result;
        $form = $this->getObject();
        $idform = $form->getId();
        if (isset($result[$idform][$id_element])) {
            return;
        }
        $result[$idform][$id_element] = true;
        if (($this->bcheckjs) and (!empty($this->regex_js))){
            $view = Hosh_View::getInstance();
            $view->JQuery_Inputmask_Regex();
            $script = '
				;
			(function($){
				$(document).ready(function(){
		            $("#'.$idform.' #'.$id_element.'").inputmask("Regex", { regex: "'.$this->regex_js.'" });
				});
			})(jQuery);	';
            $view->AddScriptDeclaration($script);
        }
        return;
    }
}