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
		if (empty($data['id'])){
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(sprintf('Object "%s" not found',$id));
        }
		return $data;
	}
}	