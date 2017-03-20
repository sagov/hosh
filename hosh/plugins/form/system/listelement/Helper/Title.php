<?php

class HoshPluginForm_System_Listelement_Helper_Title extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$transl = $form->getTranslator();
		if ($form->isEdit()){
			return 'Element # '.$form->getData('id');
		}else{
			return $transl->_('SYS_LIST_ADD_ELEMENT');
		}
	}
}		