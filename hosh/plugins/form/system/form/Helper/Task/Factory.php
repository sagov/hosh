<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Task_Factory extends Hosh_Form_Helper_Abstract
{

    function __construct ($object)
    {
        parent::__construct($object);
        
        $form = $this->getObject();
        if (! $form->isEdit()) {
            return;
        }
        
        $id = $form->getData('id');
        $hobject = new Hosh_Manager_Object($id);
        if ($hobject->isLock()) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Объект заблокирован');
            return false;
        }
    }
    
    public function render($options)
    {
        
    }
}