<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Codemirror.php 21.04.2016 18:23:41
 */
require_once 'Zend/Form/Element/Textarea.php';
/**
 * Form Element Helper
 * 
 * @category   Hosh
 * @package     Hosh_Form
 * @subpackage  Element
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Form_Element_Codemirror extends Zend_Form_Element_Textarea
{ 

        
    public function init ()
    {
        $form = $this->getAttrib('form');
        $name = $this->getName();
        $idelement = $this->getId();
        
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);        
        
        $param = array();        
        $view = Hosh_View::getInstance();
        $view->JQuery();
        $view->CodeMirror();
        
        $lineNumbers = $patten_element->get('linenumbers',true);
        if (isset($lineNumbers)){
            $param['lineNumbers'] = (boolean)($lineNumbers);
        } 
        
        $mode = $patten_element->get('mode');
        if (!empty($mode['name'])){            
            $view->CodeMirror()->AddMode($mode['name']);
            if (!empty($mode['type'])){
                $param['mode'] = $mode['type'];
            }else{
                $param['mode'] = $mode['name'];
            }
        }
        
        $placeholder = $this->getAttrib('placeholder');
        if (!empty($placeholder)){
            $view->CodeMirror()->AddScript('/addon/display/placeholder.js');
        } 

        $theme = $patten_element->get('theme');
        if (!empty($theme)){
            $view->CodeMirror()->AddTheme($theme);
            $param['theme'] = $theme;
        }
        
        $script = '
					;(function($) {
					   $(document).ready(function() {
                            if ($("#'.$idelement.'").length>0){
                               var jqObj = $("#'.$idelement.'");
					           var editor = CodeMirror.fromTextArea(jqObj[0],'.Zend_Json::encode($param).');
					        }           
		               });
		            })(jQuery);';
        $view->AddScriptDeclaration($script);
    }
    
    
    
   
}    