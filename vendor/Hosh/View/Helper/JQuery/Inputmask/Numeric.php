<?php

require_once 'Hosh/View/Helper/JQuery/Inputmask.php';

class Hosh_View_Helper_JQuery_Inputmask_Numeric extends Hosh_View_Helper_JQuery_Inputmask
{
		
	public function JQuery_Inputmask_Numeric()
	{	
		$this->JQuery_Inputmask();
		$this->view->AddScript($this->getUrlLib().'/dist/inputmask/jquery.inputmask.numeric.extensions.min.js');
		return $this;
	}	
	
}	