<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Task_LockRemove extends Hosh_Form_Helper_Abstract
{
	public function render($options)
	{
	    $form = $this->getObject();
	    $id = $form->getData('id');	    
	    $obj = new Hosh_Manager_Object_Edit();
	    return ($obj->remove($id)) ? true : false;
	}
}	