<?php

/**
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */

require_once 'Hosh/Form/Helper/Decorator/Button.php';

class Hosh_Form_Helper_Decorator_Button_Bootstrap extends Hosh_Form_Helper_Decorator_Button
{
	public function render($options){
		
		parent::render($options);
		
		$form = $this->getObject();
		$element_name = $options['element_name'];		
		$element = $form->getElement($element_name);		
		
		$this->setScript();
		$classelement = $element->getAttrib('class');
		if (!empty($classelement)) $classelement .= ' ';
		$element->setAttrib('class', $classelement.'btn btn-default');		
		
		
		return $element;
	}
	
	private function setScript(){
		static $result;
		
		if (isset($result)) return $this;
		$result = 1;
		$view = Hosh_View::getInstance(); 
		$view->Bootstrap();
		return $this;
	}
}