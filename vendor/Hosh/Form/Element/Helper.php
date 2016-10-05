<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Helper.php 21.04.2016 18:23:41
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
class Hosh_Form_Element_Helper extends Zend_Form_Element_Text
{

    /* (non-PHPdoc)
     * @see Zend_Form_Element::init()
     */
    public function init ()
    {
        $name = $this->getName();
        $this->setAttrib('data-type', 'helper');
        $this->setScript();
        
        $form = $this->getAttrib('form');
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);
        $idcategory = $patten_element->get('idcategory');
        if (isset($idcategory)) {
            $this->setAttrib('data-category', $idcategory);
        }
        
        $decorator['ViewHelper'] = array(
                'decorator' => 'ViewHelper'
        );
        $decorator['Bootstrap_Inputgroup'] = array(
                'decorator' => 'Bootstrap_Inputgroup_Addon',
                'options' => array(
                        'text' => array(                               
                                array('content'=>'<i class="fa fa-tasks" aria-hidden="true"></i>','placement'=>'prepend'),
                        )
                )
        );
        $this->addDecorators($decorator);
        $this->addValidator('Regex', false, '/[0-9A-z_]/i');
    }

    /**
     * @return Hosh_Form_Element_Helper
     */
    protected function setScript ()
    {
        static $result;
        
        $form = $this->getAttrib('form');
        $idform = $form->getId();
        
        if (isset($result[$idform])) {
            return $this;
        }
        $sname = $form->getData('sname');
        $idowner = $form->getData('idowner');
        if (empty($sname) and (! empty($idowner))) {
            $m_form = new Hosh_Manager_Form();
            $formdata = $m_form->getObject($idowner);
            $sname = $formdata['sname'];
        }
        $result[$idform] = true;
        $view = Hosh_View::getInstance();
        $view->JQuery_Inputmask_Regex();
        $config = Hosh_Config::getInstance();
        $view->AddScript('/libraries/hosh/Element/helper/script.js');
        $param = array();
        $param['url'] = $view->Hosh_Url(array('controller'=>'form','action'=>'viewhelper','idform'=>$form->getIdself()));
        $param['url_loadhelpers'] = $view->Hosh_Url(array('controller'=>'form','action'=>'gethelpers','idform'=>$sname));
        if ($form->isEdit()) {
            $param['url'] .= '&id=' . $form->getData('id');
        }
        $script = '
				;
			(function($){
				$(document).ready(function(){
		            $("#' .
                 $idform . ' [data-type=helper]").inputmask("Regex", { regex: "[0-9A-z_]*" });  
					$("#' .
                 $idform . '").Ex_Lib_Hosh_Element_Helper(' . json_encode(
                        $param) . ');
				});
			})(jQuery);	';
        $view->AddScriptDeclaration($script);
        return $this;
    }
}