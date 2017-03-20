<?php

class HoshPluginForm_System_List_Helper_Prepend extends Hosh_Form_Helper_Abstract
{
	public function render($options)
    {
		$form = $this->getObject();
		$pattern = $form->getPattern();
		$elements = $pattern->getElements();
		
		$request_http = new Zend_Controller_Request_Http;
		$helpers = array();
		foreach ($elements as $val){
			if (strtolower($val->get('type')) == 'helper'){
				$value = null;
			    if (!$form->isEdit()){
					$value = $request_http->getPost($val->get('name'),$form->getDefaultValueElement($val->get('name')));
				}else{
					$value = $request_http->getPost($val->get('name'),$form->getData($val->get('name')));
				}
				if (!empty($value)) {
                    $helpers[] = array('name'=>$val->get('name'),'value'=>$value);
				}
			}
		}		

		if (count($helpers)>0) {
		    $form->getHelper('Hosh_AddPatternElements',array('helpers'=>$helpers,'idowner'=>$form->getData('id')));
		}
		
		
		
	}
}