<?php

class Hosh_Form_Helper_Hosh_Decorator extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		if (!isset($options['value'])) return;
		
		$list = explode(',',$options['value']);
		
		foreach ($list as $val){
			$form->addDecorator($val);
		}
		return;
	}
}