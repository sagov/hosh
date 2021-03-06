<?php

abstract class Hosh_Form_Pattern_Abstract
{
	protected $_form;

	protected $_data;
	protected $_elements;
	
	
	
	function __construct(Hosh_Form_Factory $form){
		$this->_form = $form;
	}	
	
	abstract public function render();
	
		
	public function set($key,$pattern){
		$this->_data[$key] = $pattern;
		return $this;
	}
	
	public function get($key, $default = null){
	    	    
		if (isset($this->_data[$key])) {		    
		    return $this->_data[$key];
		}
		return $default;
	}
	
	public function setData($data){
		$this->_data = $data;
		return $this;
	}
	
	public function getData(){
		return $this->_data;
	}
	
	public function addElement($key,$value){
		$element = new Hosh_Form_Pattern_Element();
		$element->setData($value);
		$this->_elements[strtolower($key)] = $element;
		return $this;
	}
	
	/**
	 * @param string $key
	 * @param string $default
	 * @return Hosh_Form_Pattern_Element
	 */
	public function getElement($key, $default = false){
	    $key  = strtolower($key);
		if (isset($this->_elements[$key])) return $this->_elements[$key];
		return $default;
	}
	
		
	/**
	 * @return array
	 */
	public function getElements(){
		return $this->_elements;
	}
	
	/**
	 * @return array
	 */
	public function setElements($elements){
		$this->_elements = $elements;
		return $this;
	}
	
	
	public function setOptions($options){
		$this->set('options', $options);		
		return $this;
	}
	public function getOptions(){
		return $this->get('options');
	}
	
	public function isDisabled(){
		return $this->get('disabled',false);
	}
}