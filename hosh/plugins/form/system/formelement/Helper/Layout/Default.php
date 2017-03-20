<?php

class HoshPluginForm_System_Formelement_Helper_Layout_Default extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        
        $config = Hosh_Config::getInstance();
        $view = Hosh_View::getInstance();
        $view->Font_Fontawesome();
        $view->JQueryUi();
        $view->Bootstrap_Modal()->addHeadScript();
        $view->Hosh_Plugin_System_Form();
        $view->AddScript('/plugins/form/system/form/js/script.js');
        $view->AddScript('/plugins/form/system/formelement/js/script.js');
        $param = array();
        if ($form->isEdit()) {
            $param['url_submit'] = $view->Hosh_Url(
                    array(
                            'controller' => 'form',
                            'idform' => 'system_formelement',
                            'id' => $form->getData('id')
                    ));
        } else {
            $param['url_submit'] = $view->Hosh_Url(
                    array(
                            'controller' => 'form',
                            'idform' => 'system_formelement',
                            'idowner' => $form->getData('idowner')
                    )
                    );
        }
        $param['url_task'] = $view->Hosh_Url(
                array(
                        'controller' => 'form',
                        'action' => 'task',
                        'idform' => 'system_form',
                        'id' => $form->getData('idowner')
                )
                );
        $param['url_loadelements'] = $view->Hosh_Url(
                array(
                        'controller' => 'form',
                        'action' => 'getelements',
                        'idowner' => $form->getData('idowner')
                )
        );
        $script = '
				;
			(function($){
				$(document).ready(function(){
					$("#ElementFormModal").plugin_system_formelement_default(' .
                 json_encode($param) . ');
				});				
			})(jQuery);	';
        $view->AddScriptDeclaration($script);
        
        if ($form->isEdit()){
            $id = $form->getData('idowner');
            $obj = new Hosh_Manager_Object($id);
            if ($obj->isLock()){
                $script = '
    				;
    			(function($){
    				$(document).ready(function(){
    					$("#ElementFormModal,#ElementFormModalAffix").find("input,select,textarea,button").not("button[data-task=close]").attr("disabled",true);
    				});
    			})(jQuery);	';
                $view->AddScriptDeclaration($script);
            }
        }
    }
}	