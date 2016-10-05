<?php

class HoshPluginForm_System_Form_Helper_Prepend extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		
		if ($form->isEdit()){
		    $id = $form->getData('id');
		    if (empty($id)){
		      require_once 'Zend/Form/Exception.php';
		      throw new Zend_Form_Exception(sprintf('Объект не найден'));
		      return false;
		    }		    
		}
		
		$pattern = $form->getPattern();
		$elements = $pattern->getElements();
		
		$snamekind = $form->getData('snamekind');
		$layout = $pattern->get('layout');		
		$m_form = new Hosh_Manager_Form();
		$kinds = $m_form->getKinds();
		if (isset($kinds[$snamekind])) {
		    if (isset($kinds[$snamekind]['acl_value'])) {
		        $user  = Hosh_Manager_User_Auth::getInstance();
		        if (!$user->isAllowed($kinds[$snamekind]['acl_value'])){
		            throw new Zend_Exception('Доступ запрещен');
		            return false;
		        }
		    }
		}
		
		$request_http = new Zend_Controller_Request_Http;
		$options = array();
		foreach ($elements as $val){
			if ($val->get('type') == 'helper'){
				$value = null;
			    if (!$form->isEdit()){
					$value = $request_http->getPost($val->get('name'),$form->getDefaultValueElement($val->get('name')));
				}else{
					$value = $request_http->getPost($val->get('name'),$form->getData($val->get('name')));
				}
				if (!empty($value)) {
				    $options[] = array('name'=>$val->get('name'),'value'=>$value);
				}
			}
		}		
		
		if (count($options)>0) {
		    $form->getHelper('Hosh_AddPatternElements',$options);		
		}
		
		
		
	}
}