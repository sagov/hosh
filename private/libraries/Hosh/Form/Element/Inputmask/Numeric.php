<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Numeric.php 21.04.2016 18:23:41
 */
require_once 'Zend/Form/Element/Text.php';
/**
 * Form Element Inputmask_Numeric
 * 
 * @category   Hosh
 * @package     Hosh_Form
 * @subpackage  Element
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Form_Element_Inputmask_Numeric extends Zend_Form_Element_Text
{
    
    protected $_typedata = 'decimal';
    protected $_digits;
    protected $_groupSeparator;
    
    public function init ()
    {
        $form = $this->getAttrib('form');
        $name = $this->getName();
        $idelement = $this->getId();
        
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);        
        
        $param = array();  
        $param['rightAlign'] = false;
        $view = Hosh_View::getInstance();
        $view->JQuery_Inputmask_Numeric(); 

        
        $digits = $patten_element->get('digits',$this->_digits);
        if (!empty($digits)){
            $param['digits'] = (int)($digits);
        }
        
        $groupSeparator = $patten_element->get('groupseparator',$this->_groupSeparator);
        if (!empty($groupSeparator)){
            $param['groupSeparator'] = $groupSeparator;
            $param['autoGroup'] = true;
            $this->addFilter('Numeric',array('groupSeparator'=>$groupSeparator));
        }
        
        $this->_typedata = $patten_element->get('typedata',$this->_typedata);

        switch ($this->_typedata)
        {
            case 'integer':
                $this->addValidator('Int');
                break;
                
            default:
                break;    
        }
        
        
        $script = '
					;(function($) {
					   $(document).ready(function() {                            
                            $( "#'.$idelement.'" ).inputmask("'.$this->_typedata.'",'.Zend_Json::encode($param).');                  
		               });
		            })(jQuery);';
        $view->AddScriptDeclaration($script);        
        
    }    
   
}    