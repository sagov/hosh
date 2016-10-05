<?php

require_once 'Hosh/Form/Helper/Hosh/Db/Update.php';

class HoshPluginForm_System_Categorykind_Helper_Db_Update extends Hosh_Form_Helper_Hosh_Db_Update
{
		
	public function render($options){		
		$package = new Hosh_Manager_Db_Package_Hosh_Category_Kind();
		return $this->_update($package);		
	}
}		