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

    protected $_isbtn = true;
    protected $_issearch = true;

    public function init ()
    {
        $form = $this->getAttrib('form');
        $name = $this->getName();
        $idelement = $this->getId();
        
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);
        
        $param = $afterInit = array();
        
        $view = Hosh_View::getInstance();
        $view->Font_Fontawesome();
        $view->JQuery_Multiselect();
        
        $view->AddStyleSheet(
                '/libraries/hosh/Element/jquery/multiselect/style.css');
        
        $issearch = (boolean) ($patten_element->get('issearch', 
                $this->_issearch));
        if ($issearch) {
            $view->JQuery_Quicksearch();
            $param['selectableHeader'] = 'selectableHeader: ' .
                     Zend_Json::encode(
                            '<input type="text" class="form-control search-input" placeholder="search..." autocomplete="off">');
            $param['selectionHeader'] = 'selectionHeader: ' .
                     Zend_Json::encode(
                            '<input type="text" class="form-control search-input" placeholder="search..." autocomplete="off">');
            $afterInit[] = '
                                   var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = \'#\'+that.$container.attr("id")+\' .ms-elem-selectable:not(.ms-selected)\',
            selectionSearchString = \'#\'+that.$container.attr("id")+\' .ms-elem-selection.ms-selected\';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
            .on("keydown", function(e){
              if (e.which === 40){
                that.$selectableUl.focus();
                return false;
              }
            });
        
            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
            .on("keydown", function(e){
              if (e.which == 40){
                that.$selectionUl.focus();
                return false;
              }
          
                                 });';
            $param['afterSelect'] = 'afterSelect: function(){
                                this.qs1.cache();
                                this.qs2.cache();
                            }';
            $param['afterDeselect'] = 'afterDeselect: function(){
                                this.qs1.cache();
                                this.qs2.cache();
                                }';
        }
        
        $isbtn = (boolean) ($patten_element->get('isbtn', $this->_isbtn));
        if ($isbtn) {
            $param['selectableFooter'] = 'selectableFooter: ' .
                     Zend_Json::encode(
                            '<button class="btn btn-default ms-footer-btn" data-task="ms-select-all"><i class="fa fa-check" aria-hidden="true"></i> выбрать все</button>');
            $param['selectionFooter'] = 'selectionFooter:' .
                     Zend_Json::encode(
                            '<button class="btn btn-default ms-footer-btn" data-task="ms-deselect-all"><i class="fa fa-trash" aria-hidden="true"></i> очистить</button>');
            $afterInit[] = '
                                   var that = this;
                                   ms.on("click","[data-task=ms-select-all]",function(){
                                		that.select_all();
                                		return false;
                                	}).on("click","[data-task=ms-deselect-all]",function(){
                                		that.deselect_all();
                                		return false;
                                	});                                    
                                 ';
        }
        
        if (count($afterInit) > 0) {
            $param['afterInit'] = 'afterInit:function(ms){' .
                     implode('', $afterInit) . '}';
        }
        
        $script = '
					;(function($) {
					   $(document).ready(function() {
                           $("#' . $idelement . '").multiSelect({' .
                 implode(",", $param) . '});                            
                                
		               });
		            })(jQuery);';
        $view->AddScriptDeclaration($script);
    }
}    