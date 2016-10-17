<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_Bootstrap_Noconflict extends Zend_View_Helper_HeadScript
{

	protected $options = array(
		'file'=>'/libraries/bootstrap/noConflict.js',
	);
	
	public function Bootstrap_Noconflict($autoaddfile = true)
	{
		if ($autoaddfile) {
		    $this->addFile();
		}
		return $this;
	}
	
	public function addFile($placement = 'APPEND')
	{
		$methodjs = strtolower($placement).'File';
		$config = Hosh_Config::getInstance();
		$this->view->AddScript($config->get('public')->url.$this->options['file'],array('placement'=>$placement),false);
		return $this;
	}
	
	public function remove()
	{
		$container = $this->getContainer();		
		$config = Hosh_Config::getInstance();
		$src = $config->get('public')->url.$this->options['file'];
		foreach ($container as $key=>$val){
			if (isset($val->attributes['src']) and ($val->attributes['src'] == $src)){
				$this->__unset($key);
				break;
			}
		}		
		return $this;
	}
}	