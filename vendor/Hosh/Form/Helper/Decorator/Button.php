<?php

require_once 'Hosh/Form/Helper/Abstract.php';

/**
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */
class Hosh_Form_Helper_Decorator_Button extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$element_name = $options['element_name'];		
		$element = $form->getElement($element_name);		
		
		$result['ViewHelper'] = array('decorator'=>'ViewHelper');
		$result['HtmlTag'] = array('decorator'=>'HtmlTag','options'=>array('tag'=>'span','class'=>'Wrap-Element-Button'));
		
		$element->addDecorators($result);
		return $element;
	}
}