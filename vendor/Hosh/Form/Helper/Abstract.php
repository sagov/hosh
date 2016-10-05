<?php


abstract class Hosh_Form_Helper_Abstract
{
	
	protected $_object;
	
	/**
	 * @param Hosh_Form $object
	 */
	function __construct($object){
		$this->_object = $object;
	}
	
	function getObject(){
		return $this->_object;
	}
	
	abstract public function render($options);
}