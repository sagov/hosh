<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_Syntaxhighlighter extends Zend_View_Helper_HeadScript
{
    protected $version = '3.0.83';
    
    protected $options = array(
        'path' => '/libraries/js/syntaxhighlighter/',
    );
    
	/**
	 * @param boolean $autoload
	 * @return Hosh_View_Helper_Syntaxhighlighter
	 */
	public function Syntaxhighlighter( $autoload = true)
	{
	    $view = Hosh_View::getInstance();
	    $view->AddScript($this->getPath().'/scripts/shCore.js');
	    $view->AddStyleSheet($this->getPath().'/styles/shCoreDefault.css');
	    return $this;
	}
	
	public function getPath()
	{
	    return $this->options['path'].$this->version;
	}
	
	public function AddBrush($name)
	{
	    $view = Hosh_View::getInstance();
	    $view->AddScript($this->getPath().'/scripts/shBrush'.ucfirst(strtolower($name)).'.js');
	    return $this;
	}
}	