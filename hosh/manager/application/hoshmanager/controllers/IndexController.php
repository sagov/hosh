<?php

require_once dirName(__FILE__).'/Abstract.php';

class Hoshmanager_IndexController extends Hoshmanager_Abstract
{	
		 
	public function indexAction()
	{	
		$user = Hosh_Manager_User_Auth::getInstance();
		if ($user->isExist()){
		    $this->_forward('index', 'Cabinet');
		}else{
		    $this->_forward('login', 'Auth');
		}
	}
	
	public function jsonAction()
	{
		
	}
}