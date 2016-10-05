<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_IsEdit extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$updateparam = $form->getSettings('updateparams');
		if ($updateparam) return true; else return false;
	}
}