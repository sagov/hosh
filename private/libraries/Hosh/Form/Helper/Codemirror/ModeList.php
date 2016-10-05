<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Codemirror_ModeList extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		static $result;
						
		if (isset($result)) return $result;
		
		$form = $this->getObject();
		
		$view = Hosh_View::getInstance();
		$config = Hosh_Config::getInstance();
		$path = $config->get('path_public').$view->CodeMirror(false)->getPath().'/mode/';
		
		$result = array();
		if (!is_dir($path)) {
		    return $result;	
		}
		$dir = new Hosh_Dir();
		$listdir = $dir->getListScan($path,array('isdir'=>true,'ex_name'=>array('.','..')));
		$result[''] = '-- --';
		foreach ($listdir as $val)
		{
		    $result[$val] = $val;
		}
		
		return $result;		
	}	
	
}