<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Element_Ckeditor_GetListConfigFiles extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		static $result;
						
		if (isset($result)) {
		    return $result;
		}
		
		$form = $this->getObject();
		
		$view = Hosh_View::getInstance();
		$config = Hosh_Config::getInstance();
		$path = $config->get('public')->path.$view->Ckeditor(false)->getPath().'config/';
		
		$result = array();
		if (!is_dir($path)) {
		    return $result;	
		}
		$dir = new Hosh_Dir();
		$listdir = $dir->getListScan($path,array('isdir'=>false,'ex_name'=>array('.','..'),'ext'=>'js'));
		$result[''] = '-- --';
		foreach ($listdir as $val)
		{
		    $result[$val] = $val;
		}
		
		return $result;		
	}	
	
}