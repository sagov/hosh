<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Codemirror_ThemeList extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		static $result;
						
		if (isset($result)) return $result;
		
		$form = $this->getObject();
		
		$view = Hosh_View::getInstance();
		$config = Hosh_Config::getInstance();
		$path = $config->get('public')->path.$view->CodeMirror(false)->getPath().'/theme/';
		
		$result = array();
		if (!is_dir($path)) {
		    return $result;	
		}
		$dir = new Hosh_Dir();
		$listdir = $dir->getListScan($path,array('isdir'=>false,'ex_name'=>array('.','..'),'ext'=>'.css'));
		$result[''] = '-- --';
		foreach ($listdir as $val)
		{
		    $val = str_replace('.css', null, $val);
		    $result[$val] = $val;
		}
		
		return $result;		
	}	
	
}