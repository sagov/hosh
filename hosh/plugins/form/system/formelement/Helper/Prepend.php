<?php

class HoshPluginForm_System_Formelement_Helper_Prepend extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		
		$layout = Zend_Layout::startMvc();
		$layout->setLayout('empty');
		
		$request_http = new Zend_Controller_Request_Http;
		if (!$form->isEdit()){			
			$idowner = $request_http->getParam('idowner',null);
			$form->setData('idowner', $idowner);			
		}		
		
		$pattern = $form->getPattern();		
		$elements = $pattern->getElements();
		$helpers = array();
		foreach ($elements as $val){
			if ($val->get('type') == 'helper'){
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
		if (count($helpers)>0)	{
		    $form->getHelper('Hosh_AddPatternElements',array('helpers'=>$helpers,'idowner'=>$form->getData('idowner')));
		}
		
		$type =  $request_http->getPost('type',$form->getData('type'));
		if (!empty($type)) {
		    $form->getHelper('Hosh_AddPatternElementsType',array('value'=>$type));
		}
	}
}