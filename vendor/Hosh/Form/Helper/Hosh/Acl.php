<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_Acl extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $translate = $form->getTranslator();
        $userauth = Hosh_Manager_User_Auth::getInstance();
        
        if (isset($options['bauth'])) {
            switch ($options['bauth']) {
                case 1:
                    if (! $userauth->isExist()) {
                        require_once 'Zend/Exception.php';
                        throw new Zend_Exception($translate->_('SYS_ACCESS_DENIED'), 403);
                        return false;
                    }
                    break;
                
                case 2:
                    if ($userauth->isExist()) {
                        require_once 'Zend/Exception.php';
                        throw new Zend_Exception($translate->_('SYS_ACCESS_DENIED'), 403);
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }
        
        $acl = Hosh_Manager_Acl::getInstance();
        
        if (! empty($options['view']['value'])) {
            if (! $userauth->isAllowed($options['view']['value'])) {
                require_once 'Zend/Exception.php';
                throw new Zend_Exception($translate->_('SYS_ACCESS_DENIED'), 403);
                return false;
            }
        }
        if ($form->isEdit()) {
            $f = 'update';
        } else {
            $f = 'insert';
        }
        if (! empty($options[$f]['value'])) {
            if (! $userauth->isAllowed($options[$f]['value'])) {
                require_once 'Zend/Exception.php';
                throw new Zend_Exception($translate->_('SYS_ACCESS_DENIED'), 403);
                return false;
            }
        }
        
        return true;
    }
}