<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_JQuery_Inputmask extends Zend_View_Helper_HeadScript
{
    protected $version = '3.x';
    
    protected $options = array(
            'url_path' => '/libraries/jquery/plugins/inputmask/',
    );
    
	public function JQuery_Inputmask()
	{
		$this->view->JQuery();
		$this->view->AddScript($this->getUrlLib().'/dist/inputmask/jquery.inputmask.min.js');
		return $this;
	}
	
	public function getUrlLib()
	{
	   return $this->options['url_path'].$this->version;  
	}
}