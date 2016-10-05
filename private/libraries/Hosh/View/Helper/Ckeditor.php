<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_Ckeditor extends Zend_View_Helper_HeadScript
{
    protected $version = '4.5.11';
    
    protected $options = array(
        'path' => '/libraries/js/ckeditor/',
    );
    
	/**
	 * @param boolean $autoload
	 * @return Hosh_View_Helper_Ckeditor
	 */
	public function Ckeditor( $autoload = true)
	{
	    if (!$autoload){
	        return $this;
	    }
	    $view = Hosh_View::getInstance();	    
	    $view->AddScript($this->getPathVersion().'/ckeditor.js');	    
	    return $this;
	}
	
	
	public function getPath()
	{
	    return $this->options['path'];
	}
	
	public function getPathVersion()
	{
	    return $this->options['path'].$this->version;
	}
}	