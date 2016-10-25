<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_JQuery_Quicksearch extends Zend_View_Helper_HeadScript
{
    protected $version = '1.x';
    
    protected $options = array(
            'url_path' => '/libraries/jquery/plugins/quicksearch/',
    );
    
	public function JQuery_Quicksearch()
	{
		$this->view->JQuery();
		$this->view->AddScript($this->getUrlLib().'/jquery.quicksearch.js');		
		return $this;
	}
	
	public function getUrlLib()
	{
	   return $this->options['url_path'].$this->version;  
	}
}