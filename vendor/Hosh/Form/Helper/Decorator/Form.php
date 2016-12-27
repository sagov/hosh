<?php

/**
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright 2015
 *
 */

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Decorator_Form extends Hosh_Form_Helper_Abstract
{
	public function render($options){		
		$form = $this->getObject();
						
		$decorator_HtmlTag['tag'] = 'div';
		$decorator_HtmlTag['class'] = 'wrap-hoshform wrap-'.$form->getId();
		$form->addDecorator('FormElements')
		->addDecorator('HtmlTag',$decorator_HtmlTag)
		->addDecorator('Form');		
	}
}	