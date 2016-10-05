<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Category_Helper_GetKindList extends Hosh_Form_Helper_Abstract
{
	public function render($options){
	    $package = new Hosh_Manager_Db_Package_Hosh_Category_Kind();
		$list = $package->getList();
		$result = array();
		$result[''] = '-- --';
		foreach ($list as $val){
			$result[$val['id']] = $val['scaption'];
		}
		return $result;
	}
}	