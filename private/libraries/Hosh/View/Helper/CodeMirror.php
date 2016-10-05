<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_CodeMirror extends Zend_View_Helper_HeadScript
{
    protected $version = '5.17.0';
    
    protected $options = array(
        'path' => '/libraries/js/codemirror/',
    );
    
	/**
	 * @param boolean $autoload
	 * @return Hosh_View_Helper_CodeMirror
	 */
	public function CodeMirror( $autoload = true)
	{
	    if (!$autoload){
	        return $this;
	    }
	    $view = Hosh_View::getInstance();
	    $view->AddScript($this->getPath().'/lib/codemirror.js');
	    $view->AddStyleSheet($this->getPath().'/lib/codemirror.css');
	    return $this;
	}
	
	/**
	 * @param string $mode
	 * @return Hosh_View_Helper_CodeMirror
	 */
	public function AddMode($mode)
	{
	    $this->AddScript('/mode/'.$mode.'/'.$mode.'.js');	    
	    return $this;
	}
	
	public function AddScript($path){
	    $view = Hosh_View::getInstance();
	    $view->AddScript($this->getPath().$path);
	    return $this;
	}
	
	public function AddTheme($theme)
	{
	    $view = Hosh_View::getInstance();
	    $view->AddStyleSheet($this->getPath().'/theme/'.$theme.'.css');	    
	    return $this;
	}
	
	public function getVersion()
	{
	    return $this->version;
	}
	
	public function getPath()
	{
	    return $this->options['path'].$this->version;
	}
}	