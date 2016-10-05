<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Extension_Helper_Getlistkind extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$dbpackage = new Hosh_Manager_Db_Package_Hosh_Extension_Kind();
		$list = $dbpackage->getList();
		$result = array();
		$result[''] = '-- --';
		foreach ($list as $val){
			$result[$val['id']] = $val['sname'];
		}
		return $result;
	}
}