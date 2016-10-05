<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Value_Helper_Task_ViewAclUser extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$xhtml = $form->getHelper('View_AclUser');
		if (!isset($xhtml)){
		    $xhtml = '<div></div>';
		}			
		return $xhtml;
	}
}	