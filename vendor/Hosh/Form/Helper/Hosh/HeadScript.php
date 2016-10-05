<?php

class Hosh_Form_Helper_Hosh_HeadScript extends Hosh_Form_Helper_Abstract
{

	protected $explode = ';';
	
	public function render($options){
	    
	    $view = Hosh_View::getInstance();
	    
		if (empty($options['value']) and (empty($options['script']))) {
		    return false;
		}
		if (!empty($options['value'])){
		$list = explode($this->explode,$options['value']);
		if (count($list)>0){
			foreach ($list as $val){
				$val = trim($val);
				if (!empty($val)) {
				    $view->AddScript($val);
				}
			}
		}
		}
		
		if (!empty($options['script'])){
		    $view->AddScriptDeclaration($options['script']);
		}
		
		return true;
	}

}