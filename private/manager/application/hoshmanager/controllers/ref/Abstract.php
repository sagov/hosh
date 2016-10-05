<?php

require_once dirName(__FILE__).'/../Abstract.php';

abstract class Hoshmanager_Ref_Abstract extends Hoshmanager_Abstract
{
	protected $idform;
	protected $submit = true;
	protected $countList = 10;
	protected $acl_value;
	protected $_typemenu = 'system';
	protected $_title;
	
	protected $_leftmenu = 'index/leftmenu';
	protected $_leftmenulist = 'index/leftmenulist';
	
	public function preDispatch()
	{
	   $dispatch = parent::preDispatch();
	   if (!$dispatch){
	       return false;
	   }
	   if (!empty($this->acl_value)){
	       $user = Hosh_Manager_User_Auth::getInstance();
	       if(!$user->isAllowed($this->acl_value)){
	           require_once 'Zend/Exception.php';
	           throw new Zend_Exception('Доступ запрещен',403);
	       }
	   }
	   
	}
	
	public function indexAction(){
	
	}
	
	public function viewAction(){
		$this->_forward('view','Form',null,array('idform'=>$this->idform,'submit'=>$this->submit));
		if (!empty($this->_title)){
		    $this->view->headTitle($this->_title);
		}		
		$this->setModContent();
	}
	
	public function searchAction(){
		$this->_helper->layout->disableLayout();
		$search = $this->getRequest()->getParam('search',null);
		$param = array('search'=>$search);
		$list = $this->getList($param);
		$this->view->list = $list;
		$this->render($this->_leftmenu,null,true);
	}
	
	public function listAction(){
	    $this->_helper->layout->disableLayout();
	    $search = $this->getRequest()->getParam('search',null);
	    $page = $this->getRequest()->getParam('page', 1);
	    $param = array('search'=>$search,'page'=>$page);
	    $list = $this->getList($param);
	    $this->view->list = $list;
	    $this->render($this->_leftmenulist,null,true);
	}
	
	protected function _menuRefresh($view = 'index/leftmenulist'){
	    $this->_helper->layout->disableLayout();
	    $param = $this->getRequest()->getParams();
	    if (isset($param['id'])){
	        $this->view->idactive = $param['id'];
	    }
	    $list = $this->getList($param);
	    $this->view->list = $list;
	    $this->render($view,null,true);
	}
	
	abstract protected function setModContent();
	abstract protected function getList($param);
	
	protected function _getContentMenu($list, $param = null, $view = 'index/leftmenu.phtml'){
		$this->view->list = $list;
		if (isset($param['idactive'])) {
		    $this->view->idactive = $param['idactive'];
		}
		return '<div class="menucontent">'.$this->view->render($view).'</div>';		
	}
	
	protected function _getContentControllers($param, $view = 'index/controllers.phtml')
	{		
		foreach ($param as $key=>$val)
		{
		    switch ($key)
		    {
		        case 'addbutton':
		            $user = Hosh_Manager_User_Auth::getInstance();
		            if (!empty($param['addbutton']['acl'])){
		                if ($user->isAllowed($param['addbutton']['acl'])){
		                    $this->view->addbutton = $param['addbutton'];
		                }
		            }else{
		                $this->view->addbutton = $param['addbutton'];
		            }
		            break;
		        default:
		            $this->view->$key = $val;
		            break;
		    }
		   
		}		
		$menu = HoshManager_Model_Menu::getInstance();
		$this->view->typemenu = $this->_typemenu;
		$this->view->menus = $menu->getMenu();
		
		$userauth = Hosh_Manager_User_Auth::getInstance();
		$this->view->listhistory = $userauth->getLogList(7);
		
		return $this->view->render($view);
	}
	
	protected function _getContentFilter($param, $view = 'ref/filter/single.phtml')
	{		
		if (is_array($param))
		{
		    foreach ($param as $key=>$val){
		        $this->view->$key = $val;
		    }
		}		
		return $this->view->render($view);
	}
	
	
	protected function _setModContent($value)
	{
		$layout = $this->_helper->layout();
		$layout->leftcontent = $value;
		return $this;
	}	
	
	
	protected function _getPagination($totalcount,  $currentpage = 1, $count = 10)
	{
	    if ($totalcount > $count) {
	        $paginator = new HoshManager_Model_Paginator_Select();
	        $paginator->setTotalCount($totalcount)
	        ->setDefaultItemCountPage($count)
	        ->setCurrentPageNumber($currentpage);
	        return $paginator;
	    }
	    return false;
	}
}