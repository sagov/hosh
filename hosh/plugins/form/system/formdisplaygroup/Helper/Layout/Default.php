<?php

class HoshPluginForm_System_Formdisplaygroup_Helper_Layout_Default extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        
        $layout = Zend_Layout::startMvc();
        $layout->setLayout('empty');
        
        $config = Hosh_Config::getInstance();
        $view = Hosh_View::getInstance();
        $view->Font_Fontawesome();
        $view->JQueryUi();
        $view->Bootstrap_Modal()->addHeadScript();
        
        $view->Hosh_Plugin_System_Form();
        $view->AddScript('/plugins/form/system/form/js/script.js');
        $view->AddScript('/plugins/form/system/formdisplaygroup/js/script.js');
        $param = array();
        $updateparam = $form->getSetting('updateparams');
        if ($form->isEdit()) {
            $param['url_submit'] = $view->Hosh_Url(
                    array(
                            'controller' => 'form',
                            'idform' => 'system_formdisplaygroup',
                            'idowner' => $updateparam['idowner'],
                            'id' => $updateparam['id']
                    ));
        } else {
            $param['url_submit'] = $view->Hosh_Url(
                    array(
                            'controller' => 'form',
                            'idform' => 'system_formdisplaygroup',
                            'idowner' => $updateparam['idowner']
                    ));
        }
        $param['url_task'] = $view->Hosh_Url(
                array(
                        'controller' => 'form',
                        'action' => 'task',
                        'idform' => 'system_form',
                        'id' => $updateparam['idowner']
                ));
        
        $script = '
				;
			(function($){
				$(document).ready(function(){
					$("#DisplayGroupFormModal").plugin_system_formdisplaygroup_default(' .
                 json_encode($param) . ');
				});				
			})(jQuery);	';
        $view->AddScriptDeclaration($script);
        
        $id = $form->getData('idowner');
        $obj = new Hosh_Manager_Object($id);
        if ($obj->isLock()) {
            $script = '
    				;
    			(function($){
    				$(document).ready(function(){
    					$("#DisplayGroupFormModal").find("input,select,textarea,button").not("button[data-task=close]").attr("disabled",true);
    				});
    			})(jQuery);	';
            $view->AddScriptDeclaration($script);
        }
    }
}	