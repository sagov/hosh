<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Auth_Helper_IsEdit extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		return false;
	}
}		