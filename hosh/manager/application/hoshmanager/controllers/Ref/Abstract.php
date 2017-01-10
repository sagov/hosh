<?php

require_once dirName(__FILE__).'/../Abstract.php';

/**
 * Class Hoshmanager_Ref_Abstract
 */
abstract class Hoshmanager_Ref_Abstract extends Hoshmanager_Abstract
{
    /**
     * @var
     */
    protected $idform;
    /**
     * @var bool
     */
    protected $submit = true;
    /**
     * @var int
     */
    protected $countList = 10;
    /**
     * @var
     */
    protected $acl_value;
    /**
     * @var
     */
    protected $acl_value_remove;
    /**
     * @var
     */
    protected $acl_value_delete;
    /**
     * @var
     */
    protected $acl_value_restore;
    /**
     * @var string
     */
    protected $_typemenu = 'system';
    /**
     * @var
     */
    protected $_title;

    /**
     * @var string
     */
    protected $_leftmenu = 'index/leftmenu';
    /**
     * @var string
     */
    protected $_leftmenulist = 'index/leftmenulist';

    /**
     * @return bool
     * @throws Zend_Exception
     */
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

    /**
     *
     */
    public function indexAction(){
	
	}

    /**
     *
     */
    public function viewAction(){
		$this->_forward('view','Form',null,array('idform'=>$this->idform,'submit'=>$this->submit));
		if (!empty($this->_title)){
		    $this->view->headTitle($this->_title);
		}		
		$this->setModContent();
	}

    /**
     *
     */
    public function searchAction(){
		$this->_helper->layout->disableLayout();
		$search = $this->getRequest()->getParam('search',null);
		$param = array('search'=>$search);
		$list = $this->getList($param);
		$this->view->list = $list;
		$this->render($this->_leftmenu,null,true);
	}

    /**
     *
     */
    public function listAction(){
	    $this->_helper->layout->disableLayout();
	    $search = $this->getRequest()->getParam('search',null);
	    $page = $this->getRequest()->getParam('page', 1);
	    $param = array('search'=>$search,'page'=>$page);
	    $list = $this->getList($param);
	    $this->view->list = $list;
	    $this->render($this->_leftmenulist,null,true);
	}

    /**
     * @param string $view
     */
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

    /**
     * @return mixed
     */
    abstract protected function setModContent();

    /**
     * @param $param
     * @return mixed
     */
    abstract protected function getList($param);

    /**
     * @param        $list
     * @param null   $param
     * @param string $view
     * @return string
     */
    protected function _getContentMenu($list, $param = null, $view = 'index/leftmenu.phtml'){
		$this->view->list = $list;
		if (isset($param['idactive'])) {
		    $this->view->idactive = $param['idactive'];
		}
		return '<div class="menucontent">'.$this->view->render($view).'</div>';		
	}

    /**
     * @param        $param
     * @param string $view
     * @return string
     */
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

    /**
     * @param        $param
     * @param string $view
     * @return string
     */
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


    /**
     * @param $value
     * @return $this
     */
    protected function _setModContent($value)
	{
		$layout = $this->_helper->layout();
		$layout->leftcontent = $value;
		return $this;
	}


    /**
     * @param     $totalcount
     * @param int $currentpage
     * @param int $count
     * @return bool|HoshManager_Model_Paginator_Select
     */
    protected function _getPagination($totalcount, $currentpage = 1, $count = 10)
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

    /**
     * @param $id
     * @param $classname
     * @param $aclvalue
     * @return bool
     */
    protected function _removeObject($id, $classname, $aclvalue)
    {
        $user = Hosh_Manager_User_Auth::getInstance();
        if(!$user->isAllowed($aclvalue)){
            return false;
        }

        $hobject = new Hosh_Manager_Object($id);
        if($hobject->getClassName() == $classname and !$hobject->isSystem()){
            $package = new Hosh_Manager_Db_Package_Hosh_Object();
            $package->removeObject($id);
        }
        $this->_menuRefresh();
    }

    /**
     * @param $id
     * @param $sstatename
     * @param $classname
     * @param $aclvalue
     * @return bool
     */
    protected function _setStateObject($id, $sstatename, $classname, $aclvalue)
    {
        $user = Hosh_Manager_User_Auth::getInstance();
        if(!$user->isAllowed($aclvalue)){
            return false;
        }

        $hobject = new Hosh_Manager_Object($id);
        if($hobject->getClassName() == $classname and !$hobject->isSystem()){
            $package = new Hosh_Manager_Db_Package_Hosh_Object();
            $package->setStateName($id,$sstatename);
            $this->_menuRefresh();
            return true;
        }
        return false;
    }

    protected function _getTaskAction($val)
    {
        $user = Hosh_Manager_User_Auth::getInstance();
        $h_transl = Hosh_Translate::getInstance();
        $translate = $h_transl->getTranslate();
        $h_transl->load('form/_');
        $task = array();
        if (empty($val['bsystem'])) {
            if ($val['snamestate'] == Hosh_Manager_State::STATE_DELETE) {
                if ($user->isAllowed($this->acl_value_restore)) {
                    $task['restore'] = array('scaption' => $translate->_('SYS_SET_RESTORE'));
                }
            } else {
                if ($user->isAllowed($this->acl_value_delete)) {
                    $task['delete'] = array('scaption' => $translate->_('SYS_SET_TRASH'));
                }
            }
            if ($user->isAllowed($this->acl_value_remove)) {
                $task['remove'] = array('scaption' => $translate->_('SYS_SET_DELETE'));
            }
        }
        return $task;
    }
}