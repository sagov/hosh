<?php

class HoshPluginForm_System_Formelement_Helper_Title extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$transl = $form->getTranslator();
		if ($form->isEdit()){
			return 'Element # '.$form->getData('id');
		}else{
			return $transl->_('SYS_FORM_ADD_ELEMENT');
		}
	}
}		