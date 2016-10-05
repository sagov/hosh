<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_State_Helper_Db_GetData extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$updateparams = $form->getSettings('updateparams');
		$id = $updateparams['id'];
		$m_state = new Hosh_Manager_State();
		$classlist = $m_state->getList();
		foreach ($classlist as $val){
			if ($val['id'] == $id){
				return $val;
			}
		}
		return array();
	}
}	