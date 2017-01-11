<?php
/**
 * Hosh Manager
 *
 * @category    HoshManager
 * @package     Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: KindController.php 14.04.2016 12:51:02
 */
require_once dirName(__FILE__).'/../Abstract.php';
/**
 * Description of file_name
 *
 * @category    HoshManager
 * @package     Controller
 * @author      Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright   Copyright (c) 2016 Hosh
 *
 */
class Hoshmanager_Ref_Form_KindController extends Hoshmanager_Ref_Abstract
{
	/**
	 * @var unknown
	 */
	protected $idform = 'system_formkind';
	/**
	 * @var unknown
	 */
	protected $acl_value = 'HOSH_SYSTEM_FORM_KIND_REF';

    protected $acl_value_add = 'HOSH_SYSTEM_FORM_KIND_ADD';

    protected $acl_value_remove = 'HOSH_SYSTEM_FORM_KIND_REMOVE';

    protected $acl_value_delete = 'HOSH_SYSTEM_FORM_KIND_DELETE';

    protected $acl_value_restore = 'HOSH_SYSTEM_FORM_KIND_RESTORE';
	
	/**
	 * @return Hoshmanager_Ref_Form_KindController
	 */
	protected function setModContent(){
	    $h_translate = Hosh_Translate::getInstance();
	    $h_translate->load('form/'.$this->idform);
	    $translate = $h_translate->getTranslate();
		$id = $this->getRequest()->getParam('id',null);
		$page = $this->getRequest()->getParam('page', 1);
		$controllers = $this->_getContentControllers(
				array('addbutton'=> array(
		                'link' => $this->view->Url(),
		                'scaption' => $translate->_('SYS_NEW_FORM_TYPE'),
		                'acl'=> $this->acl_value_add
		        ))
		);				
		
		 $list = $this->getList(
                array(
                        'page' => $page
                ));		
		$menu = $this->_getContentMenu($list,array('idactive'=>$id));		
			
		$this->_setModContent(implode('',array($controllers,$menu)));
		return $this;
	}
	
	/* (non-PHPdoc)
	 * @see Hoshmanager_Ref_Abstract::getList()
	 */
	protected function getList ($param = null)
	{


	    $h_translate = Hosh_Translate::getInstance();
	    $h_translate->load('manager/_');
        $h_translate->load('form/_');
	    $translate = $h_translate->getTranslate();

	    if (! isset($param['page']) or $param['page'] < 1) {
	        $param['page'] = 1;
	    }
	    $offset = ($param['page'] - 1) * $this->countList;
	    $package = new Hosh_Manager_Form();	   
	    $list_total = $package->getKinds();
	    $list = array_slice($list_total, $offset, $this->countList);
	    foreach ($list as $key => $val) {
	        $list[$key]['href'] = $this->view->Url(
	                array(
	                        'action' => 'view',
	                        'id' => $val['id'],	
	                        'page' => $param['page'],
	                        
	                ));
	        $list[$key]['scaption'] = '# ' . $val['id'] . ' ' . $translate->_($val['scaption']);
            $list[$key]['task'] = $this->_getTaskAction($val);
	    }
	    $paginator = $this->_getPagination(count($list_total),
	            $param['page'], $this->countList);
	    if ($paginator) {
	        $this->view->paginator = $paginator->run();
	    }
	    return $list;
	}


    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_setStateObject($id, Hosh_Manager_State::STATE_DELETE, Hosh_Manager_Form::CLASSNAME_KIND, $this->acl_value_delete);
    }

    public function restoreAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_setStateObject($id, Hosh_Manager_State::STATE_NORMAL, Hosh_Manager_Form::CLASSNAME_KIND, $this->acl_value_restore);
    }

    public function removeAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_removeObject($id, Hosh_Manager_Form::CLASSNAME_KIND, $this->acl_value_remove);
    }
	
}