<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_List_Helper_ListElements extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$config = Hosh_Config::getInstance();
		$view = Hosh_View::getInstance();
		$view->JQueryUi();
		$translate = $form->getTranslator();		
		if (!$form->isEdit()){
		    return '<div class="alert alert-warning">'.$translate->_('SYS_FORM_WARNING_LISTELEMENT_INSERT').'</div>';
		}
		
		$id = $form->getData('id');
		$manager_form = new Hosh_Manager_List();
		$formdata = $manager_form->getObject($id);
		$list = $manager_form->getElements($id);
		
		$grouplistelement = array();
		if (isset($formdata['options'])){
			$options_data = Zend_Json::decode($formdata['options']);
			$form->addTranslation('list/'.$formdata['sname']);
			if (!empty($options_data['translate']['helper'])){
			     $form->getHelper($options_data['translate']['helper'],$options_data['translate']);
			}
		}
		$grouplistelement[''] = array();
		foreach ($list as $key=>$val){
			if (isset($val['options'])){
				$grouplistelement[''][$key] = $val;
			}
		}		

		
		$obj = new Hosh_Manager_Object($id);
		$result_group = array();
		foreach ($grouplistelement as $group=>$listval){			
			
			
			$result_item = array();
			foreach ($listval as $key=>$val){
				
				if (isset($val['options'])) {
				    $options_val = Zend_Json::decode($val['options']);
				}
				
				if(!empty($val['bpublic'])) {
				    $check_public = '<span class="glyphicon glyphicon-check"></span>';
				}else{
				    $check_public = '<span class="glyphicon glyphicon-unchecked"></span>';
				}
				
				$classitem = $actionitem = array();
				if (!$obj->isLock()){
				if($val['snamestate'] !== 'NORMAL') {
					$classitem[] = 'deleted';
					$actionitem['recovery'] = true;
				}else{
					$actionitem['delete'] = true;
				}
				
				if(!empty($val['bpublic'])) {
					$actionitem['unpublic'] = true;
				}else{
					$actionitem['public'] = true;
				}
				
				if (!empty($options_val['notempty'])){
					$actionitem['notempty'] = true;
				}
				if ($form->getData('bsystem') == 0){
				    $actionitem['remove'] = true;
				}
				
				$actionitem['setorder'] = true;
				
				}
				$result_item[$key] = null;
				$result_item[$key] .= '<tr data-item="sortable" id="item-element-'.$val['id'].'" class="item-element '.implode(' ',$classitem).'">';
				if (!empty($actionitem['setorder'])) {
				    $result_item[$key] .= '<td width="5px" class="setorder"><i class="fa fa-arrows-v"></i></td>';
				}else{
				    $result_item[$key] .= '<td width="5px">&nbsp;</td>';
				}				
				$result_item[$key] .= '<td width="45px">'.$val['id'].'</td>';
				$result_item[$key] .= '<td width="10px">';
				if (!empty($actionitem['notempty'])) {
				    $result_item[$key] .= '<i class="fa fa-flag" data-toggle="tooltip" title="'.$translate->_('SYS_FORM_NOTEMPTY_FIELD').'"></i>';
				} else{
				    $result_item[$key] .= '<i class="fa fa-flag-o"></i>';
				}
				$label = (!empty($options_val['options']['label'])) ? $options_val['options']['label'] : null ;
				$result_item[$key] .= '</td>';
				$result_item[$key] .= '<td width="40%" class="elementform_name">';
				$result_item[$key] .= '<a href="javascript:void(0);" data-task="edit_elementform" data-target="'.$val['id'].'" title="'.$label.'">'.$val['name'].'</a>';
				$result_item[$key] .= '</td>';
				$result_item[$key] .= '<td width="30%">'.$val['type'].'</td>';
				$result_item[$key] .= '<td class="actbutton"><div class="row">
												<div class="col-sm-6 hidden-xs">';
				if (isset($actionitem['unpublic']) or isset($actionitem['public'])){
				if (isset($actionitem['unpublic']))	{
				    $result_item[$key] .= '<a href="#" data-task="unpublic_elementform" data-target="'.$val['id'].'" data-toggle="tooltip" data-placement="bottom" title="'.$translate->_('SYS_SET_UNPUBLIC').'" >'.$check_public.'</a>';
				}
				if (isset($actionitem['public']))	{
				    $result_item[$key] .= '<a href="#" data-task="public_elementform" data-target="'.$val['id'].'" data-toggle="tooltip" data-placement="bottom" title="'.$translate->_('SYS_SET_PUBLIC').'" >'.$check_public.'</a>';
				}
				}else{
				    $result_item[$key] .= $check_public;
				}
				
				$result_item[$key] .= '			</div>
										    	<div class="col-sm-6">
												<div class="dropdown">		
												<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v" ></i></a>
												<ul class="dropdown-menu dropdown-menu-right" >
												    <li><a href="#" data-task="edit_elementform" data-target="'.$val['id'].'"><i class="fa fa-pencil fa-fw"></i> '.$translate->_('SYS_SET_EDIT').'</a></li>';
				if (isset($actionitem['recovery'])) $result_item[$key] .= '<li><a href="#" data-task="recovery_elementform" data-target="'.$val['id'].'"><i class="fa fa-undo fa-fw"></i>	'.$translate->_('SYS_SET_RESTORE').'</a></li>';
				if (isset($actionitem['unpublic']))	$result_item[$key] .= '<li><a href="#" data-task="unpublic_elementform" data-target="'.$val['id'].'"><span class="glyphicon glyphicon-unchecked"></span> '.$translate->_('SYS_SET_UNPUBLIC').'</a>';
				if (isset($actionitem['public']))	$result_item[$key] .= '<li><a href="#" data-task="public_elementform" data-target="'.$val['id'].'"><span class="glyphicon glyphicon-check"></span> '.$translate->_('SYS_SET_PUBLIC').'</a>';
				if (isset($actionitem['delete']))	$result_item[$key] .= '<li><a href="#" data-task="delete_elementform" data-target="'.$val['id'].'"><i class="fa fa-trash-o fa-fw"></i> '.$translate->_('SYS_SET_DELETE').'</a></li>';
				if (isset($actionitem['remove'])){
				    $result_item[$key] .= '<li class="divider"></li>';
				    $result_item[$key] .= '<li><a href="#" data-task="remove_elementform" data-target="'.$val['id'].'"><i class="fa fa-times"></i> '.$translate->_('SYS_SET_REMOVE').'</a></li>';
				}
				
				$result_item[$key] .= '</ul>		
												</div>
												</div>		
										   </div>
									   </td>';
				$result_item[$key] .= '</tr>';
			}
			$result_group[$group] = null;
			if (!empty($group)) $attr = ' data-item="sortable"'; else $attr = null;
			$result_group[$group] .= '<div id="displaygroup-'.$group.'" class="displaygroup-element"'.$attr.'>';
			$result_group[$group] .= '<table class="table table-hover">';
			$result_group[$group] .= implode("",$result_item);
			$result_group[$group] .= '</table>';
			$result_group[$group] .= '</div>';
			
		}
		
		
		$result = null;
		$result .= '<div class="ListElements">';
		$view->Bootstrap_Modal()->addHeadScript();
		$result .= '<div class="listelement-action-level">
						<button type="button" class="btn btn-default" data-task="new_elementform" data-target="'.$form->getData('id').'" data-toggle="tooltip" data-placement="bottom" title="'.$translate->_('SYS_LIST_ADD_ELEMENT').'"><span class="glyphicon glyphicon-th-list"></span><span class="hidden-xs">&nbsp; '.$translate->_('SYS_LIST_ADD_ELEMENT').'</span></button>						
				  </div>';
		if (count($result_group)>0){
			$result .= '<table class="table table-head">
						<tr><th width="5px"></th><th width="45px">Id</th><th width="10px"><div style="width:14px">&nbsp;</div></th><th width="40%">Name</th><th width="30%">Type</th><th>&nbsp;</th></tr>';
			$result .= '</table>';
			$result .= '<div class="displaygroups-element">'.implode("",$result_group).'</div>';			
		}
		$result .= '</div>';
		return $result;
	}
	
	protected function SortDisplayGroup($item1,$item2){
		if($item1['norder'] < $item2['norder']) return -1;
		elseif($item1['norder'] > $item2['norder']) return 1;
		else return 0;	
	}
}	