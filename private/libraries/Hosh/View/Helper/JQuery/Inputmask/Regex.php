<?php

require_once 'Hosh/View/Helper/JQuery/Inputmask.php';

class Hosh_View_Helper_JQuery_Inputmask_Regex extends Hosh_View_Helper_JQuery_Inputmask
{
		
	public function JQuery_Inputmask_Regex()
	{	
		$this->JQuery_Inputmask();
		$this->view->AddScript($this->getUrlLib().'/dist/inputmask/jquery.inputmask.regex.extensions.min.js');
		return $this;
	}	
	
}	