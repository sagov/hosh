<?php

class HoshPluginForm_System_Auth_Helper_Prepend extends Hosh_Form_Helper_Abstract
{
    public function render($options)
    {
        $user = Hosh_Manager_User_Auth::getInstance();
        if ($user->isExist()){
            $redirector = new Zend_Controller_Action_Helper_Redirector();
            $redirector->gotoUrlAndExit('?controller=cabinet');
            return;
        }
    }
}