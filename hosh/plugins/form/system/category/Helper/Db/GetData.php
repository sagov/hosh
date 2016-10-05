<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Category_Helper_Db_GetData extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$updateparams = $form->getSettings('updateparams');
		$id = $updateparams['id'];
		$manager_category = new Hosh_Manager_Category();		
		$data = $manager_category->getObject($id);
		
		return $data;
	}
}	