<?php

require_once 'Hosh/View/Helper/JQuery/Inputmask.php';

class Hosh_View_Helper_JQuery_Inputmask_Phone extends Hosh_View_Helper_JQuery_Inputmask
{
		
	public function JQuery_Inputmask_Phone()
	{	
		$this->JQuery_Inputmask();
		$this->view->AddScript($this->getUrlLib().'/dist/inputmask/jquery.inputmask.phone.extensions.min.js');
		return $this;
	}	
	
}	