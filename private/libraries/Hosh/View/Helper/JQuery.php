<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_JQuery extends Zend_View_Helper_HeadScript
{


	private $file;
	
	protected $options = array(
			'version' => '1.11.3',
			'compressed' => true,
			'cdn' => false,
			'url' => null,
	);
	


	public function JQuery($autoaddfile = true, $options = null){
				
		if (is_array($options)) $this->setOptions($options);
		$file = $this->getSrc();
		if (!empty($this->file)and($this->file != $file)){
			$item = $this->__get($file);
			if ($item) $this->__unset($file);
		}
		$this->file = $file;
		if ($autoaddfile) $this->addFile();
		return $this;
	}

	public function addFile($placement = 'PREPEND'){

		if (empty($this->file)) return $this;

		switch (strtoupper($placement))
		{
			case 'APPEND':
				$this->appendFile($this->file);
				break;

			default:
				$this->prependFile($this->file);
				break;
		}
		return $this;
	}
	
	public function remove(){
		$container = $this->getContainer();
		
		foreach ($container as $key=>$val){
			if (isset($val->attributes['src']) and ($val->attributes['src'] == $this->file)){
				$this->__unset($key);				
			}
		}		
		return $this;
	}


	public function setOptions(array $options){
		$this->options = array_marge($this->options,$options);
	}

	public function getSrc(){
		if (isset($this->options['url'])){
			$result = $this->options['url'];
		}else if($this->options['cdn']){
			$result = $this->_getGoogleCDN();
		}else{
			$result = $this->_getLocalFile();
		}
		return $result;
	}

	private function _getGoogleCDN(){
		if ($this->options['compressed']) $min = '.min'; else $min = null;
		return '//ajax.googleapis.com/ajax/libs/jquery/'.$this->options['version'].'/jquery'.$min.'.js';
	}

	private function _getLocalFile(){
		if (!empty($this->options['compressed'])) $min = '.min'; else $min = null;
		$config = Hosh_Config::getInstance();		
		return $config->get('url_public').'/libraries/jquery/release/'.$this->options['version'].'/jquery'.$min.'.js';
	}

}