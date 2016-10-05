<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Formkind_Helper_GetListLayouts extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		
		$result = $result_name = array();
		$sname = 'system_form';
		if (empty($sname)) {
		    return $result;
		}
		$config = Hosh_Config::getInstance();
		$path = $config->get('path').'/plugins/form/'.str_replace('_','/',$sname).'/Layouts';
		
		if (!is_dir($path)) {
		    return $result;	
		}
		$dir = new Hosh_Dir();
		$listdir = $dir->getListScan($path,array('isdir'=>true,'ex_name'=>array('.','..')));
		
		foreach ($listdir as $val){
			$listfile = $dir->getListScan($path.'/'.$val,array('isdir'=>false,'ext'=>'.phtml'));
			foreach ($listfile as $valfile){
				$name = $val.'/'.str_replace('.phtml','',$valfile);
				$result_name[$name] = $name;
			}
		}
		if (count($result_name)>0) {
			$result[''] = '-- --';
			$result = array_merge($result,$result_name);
		}
		return $result;		
	}	
	
}