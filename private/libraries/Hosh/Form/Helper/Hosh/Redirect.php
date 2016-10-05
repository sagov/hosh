<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_Redirect extends Hosh_Form_Helper_Abstract
{
    protected $_alertmsgdefault = 'SYS_MSG_SUCCESSFULLY';
    
	public function render($options){
		$form = $this->getObject();
		$translate = $form->getTranslator();
		if ($form->isEdit()){
			$params = $options['update'];
		}else{
			$params = $options['insert'];
		}
		
		$view = Hosh_View::getInstance();
		if (!empty($params['url'])){
			$data = $form->getDataAll();
			$url_string = $params['url'];
			//$url = preg_replace("/\{query_string\}/ei", $_SERVER['QUERY_STRING'], $url);
			$url_string = preg_replace("/:([A-Za-z0-9_]{1,})[^A-Za-z0-9_]{0}/e", "\$data['\\1']", $url_string );
			$url = $view->Hosh_Url($url_string,false);			
		}else{
			$url = "//".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];			
		}		
				
		if (!empty($url)){
			if (empty($params['string'])){
			    $params['string'] = $this->_alertmsgdefault;
			}
			
			$r = new Hosh_Controller_Action_Helper_Redirect();
			$r->redirect($url,$translate->_($params['string']),null,array('prependBase'=>false));			
		}
		return ;
	}
}	