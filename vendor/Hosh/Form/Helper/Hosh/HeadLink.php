<?php

class Hosh_Form_Helper_Hosh_HeadLink extends Hosh_Form_Helper_Abstract
{
	
	protected $explode = ';';
	
	public function render($options){
	    
	    $view = Hosh_View::getInstance();
		if (!empty($options['stylesheet'])) {
		$list = explode($this->explode,$options['stylesheet']);			
		if (count($list)>0){			
			foreach ($list as $val){
				$val = trim($val);				
				if (!empty($val)) $view->AddStyleSheet($val);
			}
		}
		}
		
		if (!empty($options['style'])){		    
		   $view->AddStyleDeclaration($options['style']);
		}
		
		return true;
	}

}