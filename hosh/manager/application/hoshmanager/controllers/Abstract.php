<?php

abstract class Hoshmanager_Abstract extends Zend_Controller_Action
{

    public function preDispatch ()
    {
        $h_transl = Hosh_Translate::getInstance();
        $h_transl->load('manager/_');
        $translate = $h_transl->getTranslate();
        $user = Hosh_Manager_User_Auth::getInstance();
        if (! $user->isExist()) {
            $alert = Hosh_Controller_Action_Helper_Alert::getInstance();
            $alert->add($translate->_('HOSH_SYS_SET_AUTH_MSG'), 'warning');
            $this->_forward('login', 'Auth');
            return false;
        } 
        return true;       
    }
}