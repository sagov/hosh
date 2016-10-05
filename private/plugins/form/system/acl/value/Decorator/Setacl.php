<?php
require_once 'Zend/Form/Decorator/HtmlTag.php';

class HoshPluginForm_System_Acl_Value_Decorator_Setacl extends Zend_Form_Decorator_HtmlTag
{

    public function render ($content)
    {
        $form = $this->getElement();
        $translate = $form->getTranslator();
        $view = Hosh_View::getInstance();
        
        if (! $form->isEdit()) {
            return $view->Bootstrap_Alert($translate->_('HOSH_SYS_ACLV_ALERT'), 
                    array(
                            'kind' => 'warning'
                    )) . $content;
        }
        
        return '<div class="wrap-hoshform_system_acl_value">' . $content .
                 $this->getViewAcl() . '</div>';
    }

    protected function getViewAcl ()
    {
        $form = $this->getElement();
        $translate = $form->getTranslator();
        $config = Hosh_Config::getInstance();
        $view = Hosh_View::getInstance();
        $view->Font_Fontawesome();
        $view->JQueryUi();
        $view->Bootstrap_Modal()->addHeadScript();
        $view->Hosh_Plugin_System_Form();
        $view->AddScript('/plugins/form/system/acl/value/js/script.js');
        $param = array();
        $param['url_task'] = $view->Hosh_Url(
                array(
                        'controller' => 'form',
                        'action' => 'task',
                        'idform' => $form->getIdSelf(),
                        'id' => $form->getData('id')
                ));
        $param['text']['update_list'] = $translate->_(
                'HOSH_SYS_ACLV_UPDATE_LIST');
        $script = '
				;
			(function($){
				$(document).ready(function(){
					$(".wrap-hoshform_system_acl_value").plugin_system_acl_value(' .
                 json_encode($param) . ');					
				});
			})(jQuery);	';
        $view->AddScriptDeclaration($script);
        
        $tab = new Hosh_Application_Tab('AclValueSettings');
        $tab->setItem('aclvaluerole', 
                '<i class="fa fa-users"></i> ' .
                         $translate->_('HOSH_SYS_ACLV_SETTING_USER_ROLES'), 
                        $this->_getViewAclRole())
            ->setItem('aclvalueuser', 
                '<i class="fa fa-user"></i> ' .
                 $translate->_('HOSH_SYS_ACLV_SETTING_USERS'), 
                $this->_getViewAclUser());
        
        $xhtml = null;
        $xhtml .= $tab->render();
        
        return $xhtml;
    }

    protected function _getViewAclRole ()
    {
        $form = $this->getElement();
        $translate = $form->getTranslator();
        $xhtml = '<div id="aclvaluerole-table">' .
                 $form->getHelper('View_AclRole') . '</div>';
        $view = Hosh_View::getInstance();
        $view->JQuery_Datetimepicker();
        $xhtml_modal = '
				<div class="form-setrole">
				<div class="setrole-row setrole-bdeny"><select name="bdeny" class="form-control"><option value="0">' .
                 $translate->_('HOSH_SYS_ACLV_ADD_ACCESS') .
                 '</option><option value="1">' .
                 $translate->_('HOSH_SYS_ACLV_CLOSE_ACCESS') .
                 '</option></select></div>
				<div class="setrole-row"><span class="setrole-label">' .
                 $translate->_('HOSH_SYS_ACLV_TO_ROLE') .
                 '</span> <span class="setrole-val valuerole"></span><input type="hidden" name="idrole" /></div>
				<div class="setrole-row"><span class="setrole-label">' .
                 $translate->_('HOSH_SYS_ACLV_TO_PERMISSION') .
                 '</span> <span class="setrole-val">"' .
                 $form->getData('scaption') .
                 '"</span></div>
				<div class="setrole-row"><span class="setrole-label">' .
                 $translate->_('HOSH_SYS_ACLV_LIMIT') . '</span> ' .
                 sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_FROM'), 
                        '<input type="text" name="dtfrom" id="dtfrom" placeholder="' .
                         $translate->_('HOSH_SYS_ACLV_NOT_LIMITED') .
                         '"  class="form-control" />') . ' ' .
                 sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_TILL'), 
                        '<input type="text" name="dttill" id="dttill" placeholder="' .
                         $translate->_('HOSH_SYS_ACLV_NOT_LIMITED') .
                         '"  class="form-control" />') . '</div>
				</div>
				';
        $xhtml_modal_button = '<button class="btn btn-primary" data-task="saveaclrolevalue">' .
                 $translate->_('SYS_SAVE') .
                 '</button>  <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">' .
                 $translate->_('SYS_CANCEL') . '</button>';
        $xhtml .= $view->Bootstrap_Modal()->render('setRoleModal', $xhtml_modal, 
                array(
                        'title' => $translate->_('HOSH_SYS_ACLV_SETTING_ACCESS'),
                        'footer' => $xhtml_modal_button
                ));
        
        return $xhtml;
    }

    protected function _getViewAclUser ()
    {
        $form = $this->getElement();
        $translate = $form->getTranslator();
        $xhtml = null;
        $xhtml .= '<div class="row">
                   <div class="col-xs-12 col-sm-6">
						<div class="input-group">
					      <input type="text" class="form-control" name="usersearch" placeholder="' .
                 $translate->_('HOSH_SYS_ACLV_SEARCH_USERS') .
                 '" />					 	
					      <span class="input-group-btn">
					        <button class="btn btn-default" type="button" data-task="setacluservalue"><i class="fa fa-plus"></i>&nbsp; ' .
                 $translate->_('HOSH_SYS_ACLV_USER_ADD_BTN') . '</button>
					      </span>					  	
					    </div>
					</div>
				</div>';
        $xhtml .= '<div id="aclvalueuser-table">' .
                 $form->getHelper('View_AclUser') . '</div>';
        
        $view = Hosh_View::getInstance();
        $view->JQuery_Datetimepicker();
        $xhtml_modal = '
				<div class="form-setrole">
				<div class="setrole-row setrole-bdeny"><select name="bdeny" class="form-control"><option value="0">' .
                 $translate->_('HOSH_SYS_ACLV_ADD_ACCESS') .
                 '</option><option value="1">' .
                 $translate->_('HOSH_SYS_ACLV_CLOSE_ACCESS') .
                 '</option></select></div>
				<div class="setrole-row"><span class="setrole-label">' .
                 $translate->_('HOSH_SYS_ACLV_TO_USER') .
                 '</span> <span class="setrole-val valueuser"><input type="text" name="searchuser" id="searchuser" class="form-control" placeholder="' .
                 $translate->_('HOSH_SYS_ACLV_SEARCH_USERS') .
                 '" /></span><input type="hidden" name="iduser" /></div>
				<div class="setrole-row"><span class="setrole-label">' .
                 $translate->_('HOSH_SYS_ACLV_TO_PERMISSION') .
                 '</span> <span class="setrole-val">"' .
                 $form->getData('scaption') .
                 '"</span></div>
				<div class="setrole-row"><span class="setrole-label">' .
                 $translate->_('HOSH_SYS_ACLV_LIMIT') . '</span> ' .
                 sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_FROM'), 
                        '<input type="text" name="dtfrom" id="dtfrom" placeholder="' .
                         $translate->_('HOSH_SYS_ACLV_NOT_LIMITED') .
                         '"  class="form-control" />') . ' ' .
                 sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_TILL'), 
                        '<input type="text" name="dttill" id="dttill" placeholder="' .
                         $translate->_('HOSH_SYS_ACLV_NOT_LIMITED') .
                         '"  class="form-control" />') . '</div>
				</div>
				';
        $xhtml_modal_button = '<button class="btn btn-primary" data-task="saveacluservalue">' .
                 $translate->_('SYS_SAVE') .
                 '</button>  <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">' .
                 $translate->_('SYS_CANCEL') . '</button>';
        $xhtml .= $view->Bootstrap_Modal()->render('setUserModal', $xhtml_modal, 
                array(
                        'title' => $translate->_('HOSH_SYS_ACLV_SETTING_ACCESS'),
                        'footer' => $xhtml_modal_button
                ));
        
        return $xhtml;
    }
}