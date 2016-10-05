<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Formdisplaygroup_Helper_IsEdit extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$updateparams = $form->getSetting('updateparams');
		if (isset($updateparams['id'])) return true; else return false;
	}
}		