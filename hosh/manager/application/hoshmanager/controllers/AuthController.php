<?php


class Hoshmanager_AuthController extends Zend_Controller_Action
{
    protected $idform = 'system_auth';
    
    
    public function loginAction ()
    {
        $user = Hosh_Manager_User_Auth::getInstance();
        if ($user->isExist()){
            $this->redirect($this->view->Url(array('controller'=>'cabinet','action'=>'index')),array('prependBase'=>false));
            return;
        }
        $h_transl = Hosh_Translate::getInstance();
        $translate = $h_transl->getTranslate();
        $h_transl->load('manager/_');
        $this->view->HeadTitle($translate->_('HM_ENTER'));
        $this->_forward('view', 'Form', null, 
                array(
                        'idform' => $this->idform
                ));        
    }
    
    public function logoutAction ()
    {
        Zend_Auth::getInstance()->clearIdentity();       
        $this->redirect($this->view->Url(array('controller'=>'auth','action'=>'login')),array('prependBase'=>false));
        $this->_helper->layout->disableLayout();
        return;
    }
}