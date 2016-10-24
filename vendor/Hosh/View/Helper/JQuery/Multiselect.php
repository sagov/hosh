<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_JQuery_Multiselect extends Zend_View_Helper_HeadScript
{
    protected $version = '0.9.12';
    
    protected $options = array(
            'url_path' => '/libraries/jquery/plugins/multiselect/',
    );
    
	public function JQuery_Multiselect()
	{
		$this->view->JQuery();
		$this->view->AddScript($this->getUrlLib().'/js/jquery.multi-select.js');
		$this->view->AddStyleSheet($this->getUrlLib().'/css/multi-select.css');
		return $this;
	}
	
	public function getUrlLib()
	{
	   return $this->options['url_path'].$this->version;  
	}
}