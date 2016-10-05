<?php

class Hoshmanager_ErrorController extends Zend_Controller_Action
{
	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');		
	    $this->getResponse()->clearBody();
	    $user = Hosh_Manager_User_Auth::getInstance();
	    if ($user->isAllowed('HOSH_SYS_IS_VIEW_TRACE_ERRORS')){	    
	       $this->view->errors = $errors;
	    }
		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = $errors->exception->getMessage();
				$this->view->errorCode = 404;
				break;
			default:
				switch ($errors->exception->getCode()){
					case 404:
						$this->getResponse()->setHttpResponseCode(404);
						$this->view->message = $errors->exception->getMessage();
						$this->view->errorCode = 404;
						break;
					case 403:
						$this->getResponse()->setHttpResponseCode(403);
						$this->view->message = 'Forbidden';
						$this->view->errorCode = 403;
						break;
					default:
						$this->getResponse()->setHttpResponseCode(500);
						$this->view->message = $errors->exception->getMessage();
						//$this->view->message = 'Application error';
						$this->view->errorCode = 500;
						break;
				}
				break;
		}
          if ($this->getInvokeArg('displayExceptions') == true) {
			$this->view->exception = $errors->exception;
		}
		
	}
}