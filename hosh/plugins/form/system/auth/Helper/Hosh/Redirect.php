<?php

class HoshPluginForm_System_Auth_Helper_Hosh_Redirect extends Hosh_Form_Helper_Hosh_Redirect
{
    protected $_alertmsgdefault = null;
    
    public function render($options)
    {
        $_controller = Zend_Controller_Front::getInstance();
        $controller = $_controller->getDefaultControllerName();
        if ($controller != 'auth'){
            $options['insert']['url'] = '?'.$_SERVER['QUERY_STRING'];
        }
        parent::render($options);
    }
}    