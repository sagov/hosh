<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Codemirror_ModeTypeList extends Hosh_Form_Helper_Abstract
{
    public function render($options)
    {
        $form = $this->getObject();
        $_element_mode_name = $form->getElement('mode_name');
        $mode_name = $_element_mode_name->getValue();
        
        $appcodemrror = new Hosh_Application_Codemirror();
        $libmode = $appcodemrror->getLibMode();   
        $this->_setScript();
                
        if (empty($libmode[$mode_name])){
            return array();
        }
        $result = array();
        foreach ($libmode[$mode_name] as $val){
            $result[$val] = $val;
        }
        return $result;
    }
    
    protected function _setScript()
    {
        static $result;
        
        $form = $this->getObject();
        $idform = $form->getAttrib('id');
        
        
        if (isset($result[$idform])){
            return $result[$idform];
        }
        $result[$idform] = true;
        $view = Hosh_View::getInstance();
        $view->AddScript('/libraries/hosh/Element/codemirror/script.js');
        
        $appcodemrror = new Hosh_Application_Codemirror();
        $libmode = $appcodemrror->getLibMode();
        $param = array();
        $param['mode'] = $libmode;        
        $script = '
				;
			(function($){
				$(document).ready(function(){
		            
					$("#' .
        					$idform . '").Ex_Lib_Hosh_Element_Codemirror(' . json_encode(
        					        $param) . ');
				});
			})(jQuery);	';
        $view->AddScriptDeclaration($script);
        return $this;
    }
}