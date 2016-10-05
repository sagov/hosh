<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Title extends Hosh_Form_Helper_Abstract
{
	public function render($options){
	$form = $this->getObject();
	$data = $form->getDataAll();
	
	$translate = $form->getTranslator();
	$view = Hosh_View::getInstance();
	$title = $xhtml = null;
		if ($form->isEdit()){
		    $title = 'Form # '.$form->getData('id');
			$xhtml =  '<h1>
			        <a href="javascript:void(0);"  data-toggle="modal" data-target="#FormInfoModal">'.$title.'</a>';			
			$idclone = $form->getData('idclone');
			if (!empty($idclone)){
			    $xhtml .= ' <i class="fa fa-long-arrow-right" aria-hidden="true"></i> <a href="'.$view->Hosh_Url(array('controller'=>'ref_form','action'=>'view','id'=>$idclone)).'"># '.$idclone.'</a>';
			}
			$xhtml .= '</h1>';
			$xhtml_modal = '<table class="table table-hover table-bordered">
			                 <tr>
			                     <td>Дата создания</td><td>'.$form->getData('dtinsert').'</td>
			                 </tr>
			                 <tr>
			                     <td>Дата обновления</td><td>'.$form->getData('dtupdate').'</td>
			                 </tr>
			                  <tr>
			                     <td>Статус</td><td>'.$form->getData('sstate').'</td>
			                 </tr>            
			               </table>';
			$xhtml .=  $view->Bootstrap_Modal()->render('FormInfoModal',$xhtml_modal,array('title'=>$title));
			
		}else{
		    $title = $translate->_('SYS_FORM_NEWFORM');
			$xhtml =  '<h1>'.$title.'</h1>';			
		}
		if (!empty($title)){
		    $view->headTitle($title);
		}
		
		return $xhtml;	
	}
}	