<?php
require_once 'Zend/Form/Decorator/HtmlTag.php';

class HoshPluginForm_System_Acl_Role_Decorator_Acl extends Zend_Form_Decorator_HtmlTag
{

    public function render($content)
    {
        $form = $this->getElement();
        if (!$form->isEdit()) {
            return $content;
        }
        return '<div class="wrap-hoshform_system_acl_role">' . $content .
            $this->getViewAcl() . '</div>';
    }

    protected function getViewAcl()
    {
        $form = $this->getElement();
        $translate = $form->getTranslator();
        $view = Hosh_View::getInstance();
        $view->Font_Fontawesome();
        $view->JQueryUi();
        $view->Bootstrap_Modal()->addHeadScript();
        $view->Hosh_Plugin_System_Form();
        $view->AddScript('/plugins/form/system/acl/role/js/script.js');
        $view->AddStyleSheet('/plugins/form/system/acl/role/css/style.css');
        $param = array();
        $param['url_task'] = $view->Hosh_Url(
            array(
                'controller' => 'form',
                'action' => 'task',
                'idform' => $form->getIdSelf(),
                'id' => $form->getData('id')
            ));
        $script = '
				;
			(function($){
				$(document).ready(function(){
					$(".wrap-hoshform_system_acl_role").plugin_system_acl_role(' .
            json_encode($param) . ');					
				});
			})(jQuery);	';
        $view->AddScriptDeclaration($script);

        $tab = new Hosh_Application_Tab('AclValueSettings');
        $tab->setItem('aclvaluerole',
            '<i class="fa fa-unlock-alt fa-lg"></i> &nbsp; ' .
            $translate->_('HOSH_SYS_ACLR_TITLE_ACLVALUE'),
            $this->viewAclValues());

        $xhtml = null;
        $xhtml .= $tab->render();

        return $xhtml;
    }

    protected function viewAclValues()
    {
        $form = $this->getElement();
        $user = Hosh_Manager_User_Auth::getInstance();
        $translate = $form->getTranslator();
        $filter_aclvalues = '
<div class="row">
    <div class="col-xs-7 col-sm-5  col-md-6">    
     <input type="text" class="form-control" placeholder="'.$translate->_('HOSH_SYS_ACLR_SEARCH').'..." value="" name="search" />    
    </div>         
    <div class="col-xs-5 col-sm-7  col-md-6">
    <div class="btn-group" data-toggle="buttons">            
      <label class="btn btn-default">
              <input type="checkbox" value="0" name="bdeny"> <i class="fa fa-check-square-o" aria-hidden="true"></i> <span class="hidden-xs">'.$translate->_('HOSH_SYS_ACLR_ACL_TRUE').'</span>
            </label>
      <label class="btn btn-default">
              <input type="checkbox" value="1" name="bdeny"> <i class="fa fa-ban" aria-hidden="true"></i> <span class="hidden-xs">'.$translate->_('HOSH_SYS_ACLR_ACL_FALSE').'</span>
            </label>
      <label class="btn btn-default">
              <input type="checkbox" value="-1" name="bdeny"> <i class="fa fa-square-o" aria-hidden="true"></i> <span class="hidden-xs">'.$translate->_('HOSH_SYS_ACLR_ACL_NONE').'</span>
            </label>            
    </div>
    </div>
        
</div>
';


        $view = Hosh_View::getInstance();
        $xhtml = null;
        $xhtml .= '<br />';
        $xhtml .= $view->Bootstrap_Alert($translate->_('HOSH_SYS_ACLR_ACLLIST_MSG'),
            array(
                'kind' => 'warning'
            ));
        $xhtml .= '<div class="filter_aclvalues"><form action="" method="post">' . $filter_aclvalues . '</form></div><br />';
        $xhtml .= '<div class="list_aclvalues">' . $form->getHelper('View_AclValue') . '</div>';

        if (!$user->isAllowed('HOSH_SYSTEM_ACL_EDIT')){
            return $xhtml;
        }
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
            '</span> <span class="setrole-val valuerole">' . $form->getData('scaption') . '</span></div>
				<div class="setrole-row"><span class="setrole-label">' .
            $translate->_('HOSH_SYS_ACLV_TO_PERMISSION') .
            '</span> <span class="setrole-val captionacl"></span><input type="hidden" name="idaclvalue" /></div>
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
}