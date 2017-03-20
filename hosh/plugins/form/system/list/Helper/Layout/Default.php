<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_List_Helper_Layout_Default extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        
        $config = Hosh_Config::getInstance();
        $view = Hosh_View::getInstance();
        $view->Font_Fontawesome();
        $view->AddStyleSheet('/plugins/form/system/list/css/stylesheet.css');
        $view->JQueryUi();
        $view->Bootstrap_Modal()->addHeadScript();
        $view->Hosh_Plugin_System_Form();
        $view->AddScript('/plugins/form/system/list/js/script.js');
        $param = array();
        if ($form->isEdit()) {
            $param['url_task'] = $view->Hosh_Url(
                    array(
                            'controller' => 'form',
                            'action' => 'task',
                            'idform' => $form->getIdSelf(),
                            'id' => $form->getData('id')
                    ));
        } else {
            $param['url_task'] = $view->Hosh_Url(
                    array(
                            'controller' => 'form',
                            'action' => 'task',
                            'idform' => $form->getIdSelf()
                    ));
        }
        $param['url_element'] = $view->Hosh_Url(
                array(
                        'controller' => 'form',                        
                        'idform' => 'system_listelement'
                ));

        $param['url_preview'] = $view->Hosh_Url(
                array(
                        'controller' => 'form',
                        'action' => 'emptylayout',
                        'idform' => $form->getData('sname')
                ));
        $script = '
				;
			(function($){
				$(document).ready(function(){					
					$("#hoshform_system_list").plugin_system_list_default(' .
                 json_encode($param) . ');
					$("#hoshform_system_list").plugin_system_list_default("init");											
				});
			})(jQuery);	';
        $view->AddScriptDeclaration($script);
        
        if ($form->isEdit()){
            $id = $form->getData('id');
            $obj = new Hosh_Manager_Object($id);
            if ($obj->isLock()){                           
                $script = '
    				;
    			(function($){
    				$(document).ready(function(){
    					$("#hoshform_system_list").find("input,select,textarea,button").not(".modal button.close").attr("disabled",true);                        
    				});
    			})(jQuery);	';
                $view->AddScriptDeclaration($script);            
            }
        }
    }
}	