<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_Public extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		
	    
		$form = $this->getObject();
		if ($form->isEdit()) $key = 'update'; else $key = 'insert';
		if (isset($options[$key]['bpublic'])){			
			return $options[$key]['bpublic'];
		}
		return ;
	}
}	