<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Task_Edit extends Hosh_Form_Helper_Abstract
{
	public function render($options)
	{
	    $form = $this->getObject();
	    $id = $form->getData('id');	    
	    $obj = new Hosh_Manager_Object($id);
	    return ($obj->Edit()) ? true : false;
	}
}	