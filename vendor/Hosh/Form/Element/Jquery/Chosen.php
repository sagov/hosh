<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Chosen.php 21.04.2016 18:23:41
 */
require_once 'Zend/Form/Element/Select.php';

/**
 * Form Element Helper
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Element
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Element_Jquery_Chosen extends Zend_Form_Element_Select
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
        $view->JQuery_Chosen();
        
        $script = '
					;(function($) {
					   $(document).ready(function() {
                          $("#' .
                 $idelement . '").chosen(' . Zend_Json::encode($param) . ');                                      
		               });
		            })(jQuery);';
        $view->AddScriptDeclaration($script);
    }
}    