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
            '<i class="fa fa-unlock-alt"></i> ' .
            $translate->_('Права'),
            $this->viewAclValues());

        $xhtml = null;
        $xhtml .= $tab->render();

        return $xhtml;
    }

    protected function viewAclValues()
    {
        $filter_aclvalues = <<< EOX
<div class="row">
    <div class="col-xs-7  col-md-6">    
     <input type="text" class="form-control" placeholder="поиск..." value="" name="search" />    
    </div>         
    <div class="col-xs-5  col-md-6">
    <div class="btn-group" data-toggle="buttons">            
      <label class="btn btn-default">
              <input type="checkbox" value="0" name="bdeny"> <i class="fa fa-check-square-o" aria-hidden="true"></i> <span class="hidden-xs">Доступно</span>
            </label>
      <label class="btn btn-default">
              <input type="checkbox" value="1" name="bdeny"> <i class="fa fa-ban" aria-hidden="true"></i> <span class="hidden-xs">Закрыто</span>
            </label>
      <label class="btn btn-default">
              <input type="checkbox" value="-1" name="bdeny"> <i class="fa fa-square-o" aria-hidden="true"></i> <span class="hidden-xs">Не назначено</span>
            </label>            
    </div>
    </div>
        
</div>
EOX;


        $form = $this->getElement();
        $view = Hosh_View::getInstance();
        $xhtml = null;
        $xhtml .= '<br />';
        $xhtml .= $view->Bootstrap_Alert('Изменение в данном списке влечет за собой изменение прав во всех дочерних профилях',
            array(
                'kind' => 'warning'
            ));
        $xhtml .= '<div class="filter_aclvalues"><form action="" method="post">'.$filter_aclvalues.'</form></div><br />';
        $xhtml .= '<div class="list_aclvalues">'.$form->getHelper('View_AclValue').'</div>';
        return $xhtml;
    }
}