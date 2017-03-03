<?php

require_once 'Zend/View/Helper/Abstract.php';

class Hosh_View_Helper_Hosh_Plugin_System_Form extends Zend_View_Helper_Abstract
{
	public function Hosh_Plugin_System_Form(){
		$this->view->Bootstrap_Modal()->addHeadScript();
		$this->view->AddScript('/libraries/hosh/plugin/system/form/1.0.1/hosh.plugin_system_form.js');
		
		return $this;
	}
}