<?php

/**
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */

require_once 'Hosh/Form/Helper/Decorator/Form/Bootstrap.php';

class Hosh_Form_Helper_Decorator_Form_Bootstrap_Horizontal extends Hosh_Form_Helper_Decorator_Form_Bootstrap
{
	public function render($options){			
		parent::render($options);	
		$form = $this->getObject();
		$_decorator = $form->getDecorator('Form');
		$_decorator->setOption('class', $_decorator->getOption('class').' form-horizontal');
		return $this;				
	}
}	