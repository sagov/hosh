<?php

/**
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */

require_once 'Hosh/Form/Helper/Decorator/Form.php';

class Hosh_Form_Helper_Decorator_Form_Bootstrap extends Hosh_Form_Helper_Decorator_Form
{
	public function render($options){	

		parent::render($options);
		
		$form = $this->getObject();	
		$form->getDecorator('Form')->setOption('role', 'form');	
		return $this;		
	}
}	