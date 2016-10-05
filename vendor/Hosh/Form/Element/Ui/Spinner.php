<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Spinner.php 21.04.2016 18:23:41
 */
require_once 'Zend/Form/Element/Text.php';
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
class Hosh_Form_Element_Ui_Spinner extends Zend_Form_Element_Text
{

    protected $_step;
    protected $_min;
    protected $_max;
    protected $_typeinput = 'decimal';
    
    public function init ()
    {
        $form = $this->getAttrib('form');
        $name = $this->getName();
        $idelement = $this->getId();
        
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);        
        
        $param = $param_inputmask = array();  
        $param_inputmask['rightAlign'] = false;
        $view = Hosh_View::getInstance();
        $view->JQueryUi();   
        $view->JQuery_Inputmask_Numeric();
        
        $step = $patten_element->get('step',$this->_step);
        if (!empty($step)){
            $param['step'] = $step;
        }
        
        $min = $patten_element->get('min',$this->_min);
        if (isset($min)){
            $param['min'] = $min;
            $param_inputmask['min'] = $min;
        }
        
        $max = $patten_element->get('max',$this->_max);
        if (isset($max)){
            $param['max'] = $max;
            $param_inputmask['max'] = $max;
        } 
        
        $this->_typeinput = $patten_element->get('typeinput',$this->_typeinput);        
        
        
        $script = '
					;(function($) {
					   $(document).ready(function() {
                            $( "#'.$idelement.'" ).spinner('.Zend_Json::encode($param).'); 
                            $( "#'.$idelement.'" ).inputmask("'.$this->_typeinput.'",'.Zend_Json::encode($param_inputmask).');                  
		               });
		            })(jQuery);';
        $view->AddScriptDeclaration($script);        
        
    }    
   
}    