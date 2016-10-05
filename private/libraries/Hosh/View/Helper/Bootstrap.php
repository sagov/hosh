<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_Bootstrap extends Zend_View_Helper_HeadScript
{


	private $file;
		
	protected $options = array(
			'version' => '3.3.4',
			'compressed' => true,
			'cdn' => false,
			'url' => null,
			'url_css' => null,
			'jquery' => true,
	);
	


	public function Bootstrap($autoaddfile = true, $options = null ){
				
		if (is_array($options)) $this->setOptions($options);
		$this->file = $this->getSrc();
		if ($autoaddfile) $this->addFile();
		return $this;
	}

	public function addFile($placement = 'APPEND'){

		if (empty($this->file)) return $this;
		
		$methodjs = strtolower($placement).'File';
		$methodcss = strtolower($placement).'Stylesheet';
		if ($this->options['jquery']) $this->view->JQuery();
		if (!empty($this->file['js'])) {
			switch (strtolower($placement))
			{
				case 'prepend':
					$this->view->Bootstrap_Noconflict(false)->addFile($placement);
					$this->$methodjs($this->file['js']);					
					break;
					
				default:
					$this->$methodjs($this->file['js']);
					$this->view->Bootstrap_Noconflict(false)->addFile($placement);
					break;
			}
						
		}
		if (!empty($this->file['css'])){
			if (is_array($this->file['css'])){
				foreach ($this->file['css'] as $val){
					$this->view->headLink()->$methodcss($val);
				}
			}else{
				$this->view->headLink()->$methodcss($this->file['css']);
			}
		}

		return $this;
	}
	
	public function remove(){
		$container = $this->getContainer();
		foreach ($container as $key=>$val){
			if (isset($val->attributes['src']) and ($val->attributes['src'] == $this->file['js'])){
				$this->__unset($key);
			}
		}
		$this->view->Bootstrap_Noconflict(false)->remove();	

		$container_css = $this->view->headLink()->getContainer();		
		foreach ($container_css as $key=>$val){
			if (isset($val->href) and (in_array($val->href,$this->file['css']))){
				$this->view->headLink()->__unset($key);
			}
		}
		return $this;
	}


	public function setOptions(array $options){
		$this->options = array_marge($this->options,$options);
	}

	public function getSrc(){
		if (isset($this->options['url'])){
			$result['js'] = $this->options['url'];			
		}else if($this->options['cdn']){
			$result = $this->_getCDN();
		}else{
			$result = $this->_getLocalFile();
		}		
		if (isset($this->options['url_css'])) $result['css'] = $this->options['url_css'];
		return $result;
	}

	private function _getCDN(){
		if ($this->options['compressed']) $min = '.min'; else $min = null;
		$result['js'] =  '//maxcdn.bootstrapcdn.com/bootstrap/'.$this->options['version'].'/js/bootstrap'.$min.'.js';
		$result['css'][] = '//maxcdn.bootstrapcdn.com/bootstrap/'.$this->options['version'].'/css/bootstrap'.$min.'.css';
		$result['css'][] = '//maxcdn.bootstrapcdn.com/bootstrap/'.$this->options['version'].'/css/bootstrap-theme'.$min.'.css';
		return $result;
	}

	private function _getLocalFile(){
		if (!empty($this->options['compressed'])) $min = '.min'; else $min = null;
		$config = Hosh_Config::getInstance();
		$result['js'] = $config->get('url_public').'/libraries/bootstrap/'.$this->options['version'].'/js/bootstrap'.$min.'.js';
		$result['css'][] = $config->get('url_public').'/libraries/bootstrap/'.$this->options['version'].'/css/bootstrap'.$min.'.css';
		$result['css'][] = $config->get('url_public').'/libraries/bootstrap/'.$this->options['version'].'/css/bootstrap-theme'.$min.'.css';
		return $result;
	}

}