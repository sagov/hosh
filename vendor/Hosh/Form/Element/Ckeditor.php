<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Ckeditor.php 21.04.2016 18:23:41
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
class Hosh_Form_Element_Ckeditor extends Zend_Form_Element_Textarea
{ 

    protected $_uicolor = '#f0f0f0';
    protected $_customconfig = 'default.js';
        
    public function init ()
    {
        $form = $this->getAttrib('form');
        $name = $this->getName();
        $idelement = $this->getId();
        
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);        
        
        $param = array(); 
        $config = Hosh_Config::getInstance();
        $view = Hosh_View::getInstance();        
        $view->Ckeditor();

        $customconfig = $patten_element->get('customconfig',$this->_customconfig);
        if (isset($customconfig)){            
            $param['customConfig'] = $config->get('public')->url.$view->Ckeditor(false)->getPath().'/config/'.$customconfig;
        }
        
        $uicolor = $patten_element->get('uicolor',$this->_uicolor);
        if (isset($uicolor)){
            $param['uiColor'] = $uicolor;
        }
        
        
        $script = '
					;(function($) {
					   $(document).ready(function() {
                            if ($("#'.$idelement.'").length>0){                                   
                               CKEDITOR.replace( "'.$name.'" , '.Zend_Json::encode($param).');                                       
					        }           
		               });
		            })(jQuery);';
        $view->AddScriptDeclaration($script);
    }    
   
}    