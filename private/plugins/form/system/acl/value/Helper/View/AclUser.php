<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Value_Helper_View_AclUser extends Hosh_Form_Helper_Abstract
{
	public function render($options){
		$form = $this->getObject();
		$form->addTranslation('manager/_');
		$translate = $form->getTranslator();
		$aclvalue = new Hosh_Manager_Acl_Value($form->getData('id'));
		$listacl = $aclvalue->getAclUser();	

		$xhtml_item = array();
		foreach ($listacl as $val){
		    $icon = $classitem = null;
		    
		        switch ($val['bdeny'])
		        {
		            case 0:
		                $icon['state'] = '<span class="glyphicon glyphicon-ok"></span>';
		                break;
		    
		            default:
		                $icon['state'] = '<span class="glyphicon glyphicon-remove"></span>';
		                break;          
		               
		        }
		        $dtime = array();
		        if (isset($val['dtfrom']))	{
		            $dtime[] = sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_FROM'),$val['dtfrom']);
		        }
		        if (isset($val['dttill']))	{
		            $dtime[] = sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_TILL'),$val['dttill']);
		        }
		        if (count($dtime)>0){
		            $icon['time'] = '<span class="glyphicon glyphicon-time" data-toggle="tooltip" data-placement="top" title="'.implode(' ',$dtime).'"></span>';
		        }
		        $icon['remove'] = '<a href="#" data-task="removeacluser" data-target="'.$val['idowner'].'" data-toggle="tooltip" data-placement="auto" title="'.$translate->_('SYS_SET_DELETE').'"><span class="glyphicon glyphicon-trash"></span></a>';
		        $icon['edit'] = '<a href="#" data-task="setacluservalue" data-target="'.$val['idowner'].'" data-toggle="tooltip" data-placement="auto" title="'.$translate->_('SYS_SET_EDIT').'"><span class="glyphicon glyphicon-edit"></span></a>';
		        $param = array('bdeny'=>$val['bdeny'],'dtfrom'=>$val['dtfrom'],'dttill'=>$val['dttill']);
		   $item = null;
		   $item .= '<tr class="item-user item-user-'.$val['idowner'].'" rel=\''.Zend_Json::encode($param).'\'>';
		   $item  .= '<td class="state">';
		   if (isset($icon['state'])) $item  .= $icon['state']; else $item .= '&nbsp;';
		   $item  .= '</td>';
		   $item .= '<td><span class="caption-user">'.$val['suser'].'</span> (<span class="caption-username">'.$val['susername'].'</span>)'.'</td>';
		   $item  .= '<td>';
		   if (isset($icon['time'])) $item  .= $icon['time']; else $item .= '&nbsp;';
		   $item  .= '</td>';		   
		   $item  .= '<td>'.$icon['edit'].' &nbsp; '.$icon['remove'].'</td>';
		   $item .= '</tr>';
		   $xhtml_item[] = $item;
		}
		if (count($xhtml_item) == 0) {
		    return ;
		}
		
		$xhtml = null;
		$xhtml .= '<table class="table items-user">'.implode('',$xhtml_item).'</table>';
		return $xhtml;
	}
}