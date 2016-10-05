<?php

class HoshPluginForm_System_Formdisplaygroup_Helper_Title extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		if ($form->isEdit()){
			return 'Form group # '.$form->getData('id');
		}else{
			return 'New form group';
		}
	}
}		