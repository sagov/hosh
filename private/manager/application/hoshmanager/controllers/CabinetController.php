<?php

require_once dirName(__FILE__).'/Abstract.php';

class Hoshmanager_CabinetController extends Hoshmanager_Abstract
{	
		 
	public function indexAction()
	{	
	    $menu = HoshManager_Model_Menu::getInstance();
	    $menu = $menu->getMenu();
	    $this->view->menulist = $menu;
	    $h_transl = Hosh_Translate::getInstance();
	    $translate = $h_transl->getTranslate();
	    $this->view->HeadTitle($translate->_('HM_CABINET'));
	    $userauth = Hosh_Manager_User_Auth::getInstance();
	    $this->view->loglist = $userauth->getLogList(7);
	}
	

}