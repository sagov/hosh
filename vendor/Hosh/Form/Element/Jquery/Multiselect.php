<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Multiselect.php 21.04.2016 18:23:41
 */
require_once 'Zend/Form/Element/Multiselect.php';

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
class Hosh_Form_Element_Jquery_Multiselect extends Zend_Form_Element_Multiselect
{

    protected $_isbtn = false;

    public function init ()
    {
        $form = $this->getAttrib('form');
        $name = $this->getName();
        $idelement = $this->getId();
        
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);
        
        $param = array();
        
        $view = Hosh_View::getInstance();
        $view->JQuery_Multiselect();
        $view->AddStyleSheet(
                '/libraries/hosh/Element/jquery/multiselect/style.css');
        
        $isbtn = (boolean)($patten_element->get('isbtn', $this->_isbtn));
        if ($isbtn) {
            $param['selectableFooter'] = 'selectableFooter: '.Zend_Json::encode('<button class="btn btn-default ms-footer-btn" data-task="ms-select-all"><i class="fa fa-check" aria-hidden="true"></i> выбрать все</button>');
            $param['selectionFooter'] = 'selectionFooter:'.Zend_Json::encode('<button class="btn btn-default ms-footer-btn" data-task="ms-deselect-all"><i class="fa fa-trash" aria-hidden="true"></i> очистить</button>');
            $param['afterInit'] = 'afterInit:function(ms){
                                    var that = this;
                                   ms.on("click","[data-task=ms-select-all]",function(){
                                		that.select_all();
                                		return false;
                                	}).on("click","[data-task=ms-deselect-all]",function(){
                                		that.deselect_all();
                                		return false;
                                	});
                                    
                                 }';
        }
        
        $script = '
					;(function($) {
					   $(document).ready(function() {
                           $("#' . $idelement . '").multiSelect({'.implode(",",$param).'});                            
                                
		               });
		            })(jQuery);';
        $view->AddScriptDeclaration($script);
    }
}    