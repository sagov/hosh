<?php

require_once 'Zend/View/Helper/HeadScript.php';

class Hosh_View_Helper_AddScriptDeclaration extends Zend_View_Helper_HeadScript
{	
	protected $_placement = 'APPEND';
	
	public function AddScriptDeclaration( $text, $options = array())
	{
		if (empty($text)) return false;
		
		if (isset($options['placement'])) {
		    $this->_placement = $options['placement'];
		}
				
		switch (strtoupper($this->_placement))
		{
			case 'PREPEND':
				$this->prependScript($text);
				break;
		
			default:
				$this->appendScript($text);				
				break;
		}
	}
}