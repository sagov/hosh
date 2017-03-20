<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Listelement_Helper_Db_GetData extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$updateparams = $form->getSettings('updateparams');
		$id = $updateparams['id'];
		$packageform_element = new Hosh_Manager_Db_Package_Hosh_List_Element();
		$form_data_element = $packageform_element->getObject($id);

		
		$result_data = $form_data_element;
		if (isset($form_data_element['options'])){
			unset($result_data['options']);
			$options = json_decode($form_data_element['options'],true);
			$result_data = array_merge($result_data,$options);
		}
		
		$arr_result = $result = array();
		foreach ($result_data as $key_1=>$val_1){
			if (is_array($val_1)){
				$this->toArray($key_1,$val_1,$arr_result);
			}else{
				$result[strtolower($key_1)] = $val_1;
			}
		}
		$result = array_merge($result,$arr_result);
		
		return $result;
	}
	
	protected function toArray($keyname,$arr,& $result = array()){
		if ($keyname) $preff = $keyname.'_'; else $preff = null;
		foreach ($arr as $key=>$val){
			if (is_array($val)){
				$this->toArray(strtolower($preff.$key),$val,$result);
			}else{
				$result[strtolower($preff.$key)] = $val;
			}
		}
	
	}
}	