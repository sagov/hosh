<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Formdisplaygroup_Helper_Init extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$updateparams = $form->getSetting('updateparams');
		$request_http = new Zend_Controller_Request_Http;
		$idowner = $request_http->getParam('idowner');
		if (!empty($idowner)){
			$updateparams['idowner'] = $idowner;
			$form->setSetting('updateparams',$updateparams);
		}		
	}
}	