<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Text_Toarray extends Hosh_Form_Helper_Abstract
{
	
	protected $_explode_line = "\n";
	protected $_explode = '=';

	public function render($options){		
		$result = array();
		
		if (!empty($options['value'])) {			
			$options['value'] = trim($options['value']);
			$rows = explode($this->_explode_line,$options['value']);
			foreach ($rows as $row){
				$line = explode($this->_explode,$row);				
				if (isset($line[1])) $text = trim($line[1]); else $text = null;
				if (isset($line[0])) $key = trim($line[0]); else $key = null;				
				$result[$key] = $text;				
			}
		}		
		return $result;
	}

}