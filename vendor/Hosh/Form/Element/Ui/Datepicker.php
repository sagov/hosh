<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Codemirror.php 21.04.2016 18:23:41
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
class Hosh_Form_Element_Ui_Datepicker extends Zend_Form_Element_Text
{

    protected $_changemonth = true;
    protected $_changeyear = true;
    protected $_showanim;
    protected $_showothermonths = false;
    protected $_selectothermonths = false;
    protected $_showbuttonpanel = false;
    protected $_numberofmonths;
    protected $_dateformat = 'dd.mm.yy';
    protected $_showweek = false;
    protected $_mindate;
    protected $_maxdate;
    protected $_range;
    
    public function init ()
    {
        $form = $this->getAttrib('form');
        $name = $this->getName();
        $idelement = $this->getId();
        
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);        
        
        $param = array();        
        $view = Hosh_View::getInstance();
        $view->JQueryUi(); 

        $changeMonth = (boolean)($patten_element->get('changemonth',$this->_changemonth));
        if ($changeMonth){
            $param['changeMonth'] = $changeMonth;
        }
        $changeYear = (boolean)($patten_element->get('changeyear',$this->_changeyear));
        if ($changeYear){
            $param['changeYear'] = $changeYear;
        }
        $showAnim = $patten_element->get('showanim',$this->_showanim);
        if (!empty($showAnim)){
            $param['showAnim'] = $showAnim;
        }
        $showOtherMonths = (boolean)($patten_element->get('showothermonths',$this->_showothermonths));
        if ($showOtherMonths){
            $param['showOtherMonths'] = $showOtherMonths;
        }
        $selectOtherMonths = (boolean)($patten_element->get('selectothermonths',$this->_selectothermonths));
        if ($selectOtherMonths){
            $param['selectOtherMonths'] = $selectOtherMonths;
        }
        $showButtonPanel = (boolean)($patten_element->get('showbuttonpanel',$this->_showbuttonpanel));
        if ($showButtonPanel){
            $param['showButtonPanel'] = $showButtonPanel;
        }
        $numberOfMonths = $patten_element->get('numberofmonths',$this->_numberofmonths);
        if (!empty($numberOfMonths)){
            $param['numberOfMonths'] = (int)($numberOfMonths);
        }
        $dateFormat = $patten_element->get('dateformat',$this->_dateformat);
        if (!empty($dateFormat)){
            $param['dateFormat'] = $dateFormat;
        }
        $showWeek = (boolean)($patten_element->get('showweek',$this->_showweek));
        if ($showWeek){
            $param['showWeek'] = $showWeek;
        }

        $minDate = $patten_element->get('mindate',$this->_mindate);
        if (!empty($minDate)){
            $param['minDate'] = $minDate;
        }
        
        $maxDate = $patten_element->get('maxdate',$this->_maxdate);
        if (!empty($maxDate)){
            $param['maxDate'] = $maxDate;
        }

        $range = $patten_element->get('range',$this->_range); 
        $script_d = null;
        if (isset($range['type']) and !empty($range['depend']))
        {
            $typeDate = ($range['type'] == 'min') ? 'minDate' : 'maxDate';
            
            $script_d = '$( "#'.$idelement.'" ).on("change",function(){
                            $("input[name='.$range['depend'].']").datepicker( "option", "'.$typeDate.'", $( "#'.$idelement.'" ).val() );
                        });
                        $("input[name='.$range['depend'].']").datepicker( "option", "'.$typeDate.'", $( "#'.$idelement.'" ).val() );';
            
        }
        
        $script = '
					;(function($) {
					   $(document).ready(function() {
                            datepicker_'.$name.' = $( "#'.$idelement.'" ).datepicker('.Zend_Json::encode($param).');  
                           '.$script_d.'                 
		               });
		            })(jQuery);';
        $view->AddScriptDeclaration($script);
        
        $decorator['ViewHelper'] = array(
                'decorator' => 'ViewHelper'
        );
        $decorator['Bootstrap_Inputgroup'] = array(
                'decorator' => 'Bootstrap_Inputgroup_Addon',
                'options' => array(
                        'text' => array(
                                array('content'=>'<i class="fa fa-calendar" aria-hidden="true"></i>','placement'=>'prepend'),
                        )
                )
        );
        $this->addDecorators($decorator);
    }

   
   
}    