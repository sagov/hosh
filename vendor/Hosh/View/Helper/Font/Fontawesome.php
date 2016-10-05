<?php

require_once 'Zend/View/Helper/HeadLink.php';

class Hosh_View_Helper_Font_Fontawesome extends Zend_View_Helper_HeadLink
{
	protected $options = array(
		'version'=>'4.6.3',
		'localpath'=>'/libraries/fonts/fontawesome/',
		'filecss'=>'font-awesome.min.css',	
		'maxcdn' => false,					
	);
	
	public function Font_Fontawesome()
	{
		$path = $this->getPath();
		$this->view->AddStyleSheet($path);
		return $this;
	}
	
	public function setOptions(array $options){
		$this->options = array_merge($this->options,$options);
	}
	
	public function getOptions($key = null){
		if (isset($key)){
		if (isset($this->options[$key])) return $this->options[$key]; else return false;
		}else{
			return $this->options;
		}		
	}
	
	protected function getPath(){
		if ($this->options['maxcdn']){
			return $this->getMaxCDN();
		}else{
			return $this->options['localpath'].$this->options['version'].'/css/'.$this->options['filecss'];
		}
	}
	
	protected function getMaxCDN(){
		return '//maxcdn.bootstrapcdn.com/font-awesome/'.$this->options['version'].'/css/font-awesome.min.css';
	}
}