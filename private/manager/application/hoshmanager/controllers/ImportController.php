<?php


class Hoshmanager_ImportController extends Zend_Controller_Action
{
    protected $idform = 'system_import';
    
    
    public function indexAction ()
    {
        $layout = $this->getRequest()->getParam('layout', null);
        if ($layout == 'empty'){
            $this->_helper->layout->setLayout('empty');
        }        
        $this->_forward('view', 'Form', null, 
                array(
                        'idform' => $this->idform
                ));        
    }   
    
    
}