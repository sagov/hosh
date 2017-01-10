<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Value_Helper_View_AclRole extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$form->addTranslation('manager/_');
		$translate = $form->getTranslator();
		$aclvalue = new Hosh_Manager_Acl_Value($form->getData('id'));
		$listacl = $aclvalue->getAcl();
		
		
		$aclrole = array();
		foreach ($listacl as $val){
			if ($val['skind'] == 'r'){
				$aclrole[$val['idowner']] = $val;
			}
		}
		
		
		$role = new Hosh_Manager_Acl_Role();
		$adapter = $role->getAdapter();
		$list = $adapter->getList();
		$applist = Hosh_Application_List::getInstance();
		$treelist = $applist->toTree($list);
		$acltreelist = array();
		foreach ($treelist as $key=>$val){
			if (isset($aclrole[$val['id']])){
				$acltreelist[$val['id']] = $aclrole[$val['id']];
				$acltreelist[$val['id']]['isset'] = true;
			}else if (isset($acltreelist[$val['idparent']])){
				$acltreelist[$val['id']] = $acltreelist[$val['idparent']];
				$acltreelist[$val['id']]['isset'] = false;
			}
		}
		
		$xhtml_item = array();
		$nlevel = 0;
		foreach ($treelist as $val){
			$icon = $classitem = null;
			if (isset($acltreelist[$val['id']])){
				switch ($acltreelist[$val['id']]['bdeny'])
				{
					case 0:
						$icon['state'] = '<span class="glyphicon glyphicon-ok"></span>';
						break;
		
					case 1:
						$icon['state'] = '<span class="glyphicon glyphicon-remove"></span>';
						break;
				}
				$dtime = array();
				if (isset($acltreelist[$val['id']]['dtfrom']))	{
				    $dtime[] = sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_FROM'),$acltreelist[$val['id']]['dtfrom']);
				}
				if (isset($acltreelist[$val['id']]['dttill']))	{
				    $dtime[] = sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_TILL'),$acltreelist[$val['id']]['dttill']);
				}
				if (count($dtime)>0){
					$icon['time'] = '<span class="glyphicon glyphicon-time" data-toggle="tooltip" data-placement="top" title="'.implode(' ',$dtime).'"></span>';
				}
				if ($acltreelist[$val['id']]['isset']) {					
					$icon['remove'] = '<a href="#" data-task="removeaclrole" data-target="'.$val['id'].'" data-toggle="tooltip" data-placement="auto" title="'.$translate->_('SYS_SET_DELETE').'"><span class="glyphicon glyphicon-trash"></span></a>';
				}
			}
			if (isset($acltreelist[$val['id']]['isset'])) {
				$icon['edit'] = '<a href="#" data-task="setaclrolevalue" data-target="'.$val['id'].'"  data-toggle="tooltip" data-placement="auto" title="'.$translate->_('SYS_SET_EDIT').'"><span class="glyphicon glyphicon-edit"></span></a>';
				$classitem = ' item-set';
			}else{
				$icon['edit'] = '<a href="#" data-task="setaclrolevalue" data-target="'.$val['id'].'" data-toggle="tooltip" data-placement="auto" title="'.$translate->_('HOSH_SYS_ACLV_SETTING_ACCESS').'"><span class="glyphicon glyphicon-plus"></span></a>';
			}
			if (!isset($icon['remove'])) $icon['remove'] = null;
			if (isset($acltreelist[$val['id']]['isset'])) {
				$param = array('bdeny'=>$acltreelist[$val['id']]['bdeny'],'dtfrom'=>$acltreelist[$val['id']]['dtfrom'],'dttill'=>$acltreelist[$val['id']]['dttill']);
			}else{
				$param = array();
			}
			$item = null;
			$item  .= '<tr class="item-role item-role-'.$val['id'].''.$classitem.'" rel=\''.Zend_Json::encode($param).'\'>';
			$item  .= '<td class="state">';
			if (isset($icon['state'])) $item  .= $icon['state']; else $item .= '&nbsp;';
			$item  .= '</td>';
			$item  .= '<td>'.$applist->getLevelCaption($val['level']).'<span class="caption-role">'.$translate->_($val['scaption']).' ('.$val['sname'].')</span></td>';
			$item  .= '<td>';
			if (isset($icon['time'])) $item  .= $icon['time']; else $item .= '&nbsp;';
			$item  .= '</td>';
				
			$item  .= '<td>'.$icon['edit'].' &nbsp; '.$icon['remove'].'</td>';
				
			$item  .= '</tr>';
			$xhtml_item[] = $item;
		}
		if (count($xhtml_item) == 0) return;
		
		
		$xhtml = null;
		$xhtml .= '<table class="table items-role">'.implode('',$xhtml_item).'</table>';
		return $xhtml;
	}
}