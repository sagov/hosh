<?php

class HoshPluginForm_System_Auth_Helper_Login extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        Zend_Loader::loadClass('Zend_Filter_StripTags');
        $f = new Zend_Filter_StripTags();
        $request_http = new Zend_Controller_Request_Http();
        $username = $f->filter($request_http->getPost('susername'));
        $password = $f->filter($request_http->getPost('spassword'));
        
                
        $model_table_user = new Hosh_Manager_Db_Package_Hosh_User();
        $dbname = $model_table_user->info('name');
        $dbAdapter = $model_table_user->getAdapter();
        
        // setup Zend_Auth adapter for a database table
        Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        
        $authAdapter->setTableName($dbname);
        $authAdapter->setIdentityColumn('susername');
        $authAdapter->setCredentialColumn('spassword');
        
        $authAdapter->setIdentity($username);
        $authAdapter->setCredential(md5($password));
        
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        if ($result->isValid()) {
            // success: store database row to auth's storage
            // system. (Not the password though!)
            $data = $authAdapter->getResultRowObject(null, 'spassword');
            $auth->getStorage()->write($data);
            return true;
        }else{
            $translator = $form->getTranslator();
            $form->addErrorMessage($translator->_('HOSH_SYS_AUTH_ERROR_LOGIN_MSG'));
            return false;
        }
        
        return false;
    }
}