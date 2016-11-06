<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_JQuery_Chosen extends Zend_View_Helper_HeadScript
{
    protected $version = '1.6.2';
    
    protected $options = array(
            'url_path' => '/libraries/jquery/plugins/chosen/',
    );
    
	public function JQuery_Chosen()
	{
		$this->view->JQuery();
		$this->view->AddScript($this->getUrlLib().'/chosen.jquery.min.js');
		$this->view->AddStyleSheet($this->getUrlLib().'/chosen.min.css');
		return $this;
	}
	
	public function getUrlLib()
	{
	   return $this->options['url_path'].$this->version;  
	}
}