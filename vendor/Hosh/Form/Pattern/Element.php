<?php

class Hosh_Form_Pattern_Element
{
	protected $_data;
	
	public function setData($data)
	{
		$this->_data = $data;
		return $this;
	}
	
	public function getData(){
		return $this->_data;
	}
	
	public function set($key,$pattern){
		$this->_data[$key] = $pattern;
		return $this;
	}
	
	public function get($key, $default = null){
		if (isset($this->_data[$key])) return $this->_data[$key];
		return $default;
	}
	
	public function setType($value){
		$this->set('type', $value);
		return $this;
	}
}