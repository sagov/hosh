<?php
/**
 * Hosh Manager
 *
 * @category    HoshManager  
 * @package     Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: FormController.php 14.04.2016 12:51:02
 */
require_once 'Abstract.php';
/**
 * Description of file_name
 * 
 * @category    HoshManager  
 * @package     Controller
 * @author      Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright   Copyright (c) 2016 Hosh 
 *
 */
class Hoshmanager_Ref_FormController extends Hoshmanager_Ref_Abstract
{

    /**
     * @var unknown
     */
    protected $idform = 'system_form';

    /**
     * @var unknown
     */
    protected $acl_value = 'HOSH_SYSTEM_FORM_REF';

    protected $acl_value_add = 'HOSH_SYSTEM_FORM_ADD';

    protected $acl_value_remove = 'HOSH_SYSTEM_FORM_REMOVE';

    protected $acl_value_delete = 'HOSH_SYSTEM_FORM_DELETE';

    protected $acl_value_restore = 'HOSH_SYSTEM_FORM_RESTORE';
    
   

    /* (non-PHPdoc)
     * @see Hoshmanager_Ref_Abstract::viewAction()
     */
    public function viewAction ()
    {
        $id = $this->getRequest()->getParam('id', null);
        if (isset($id)) {
            $this->submit = null;
        }
        parent::viewAction();
    }

    /**
     *
     * @return Hoshmanager_Form_SystemController
     */
    protected function setModContent ()
    {
        $search = $this->getRequest()->getParam('search', null);
        $page = $this->getRequest()->getParam('page', 1);
        $id = $this->getRequest()->getParam('id', null);
        
        $h_transl = Hosh_Translate::getInstance();
        $translate = $h_transl->getTranslate();
        $adapter_transl = $translate->getAdapter();
        $h_transl->load('form/system_form');
        $view = Hosh_View::getInstance();
        $modal = $view->Bootstrap_Modal();
        $modal->addHeadScript();
        $controllers = $this->_getContentControllers(
                array(
                        'addbutton' => array(
                                'link' => $this->view->Url(),
                                'scaption' => $adapter_transl->_('SYS_FORM_NEWFORM'),
                                'acl' => $this->acl_value_add
                        ),
                        'subaddbutton' => array('<a href="'.$this->view->Url(array('controller'=>'import','action'=>'index','layout'=>'empty')).'" title="'.$translate->_('HM_IMPORT').'" class="hosh-modal" rel=\'{"iframe":true}\'>'.$translate->_('HM_IMPORT').'</a>'),
                ));
        
        $filtermenu = $this->_getContentFilter(
                array(
                        'search_url' => $this->view->Url(
                                array(
                                        'action' => 'search'
                                )),
                        'search_placeholder' => $translate->_('HM_SEARCH_SNAME')
                ));
        
        $list = $this->getList(
                array(
                        'search' => $search,
                        'page' => $page
                ));
        $menu = $this->_getContentMenu($list, 
                array(
                        'idactive' => $id
                ));
        if (!empty($id)){
            $menu_form = $this->view->render('ref/form/menuform.phtml');
        }else{
            $menu_form = null;
        }
        
        $this->_setModContent(
                implode('', 
                        array(
                                $controllers,
                                $filtermenu,
                                $menu,
                                $menu_form
                        )));
        return $this;
    }

    /* (non-PHPdoc)
     * @see Hoshmanager_Ref_Abstract::getList()
     */
    protected function getList ($param)
    {
        $h_transl = Hosh_Translate::getInstance();
        $translate = $h_transl->getTranslate();
        $h_transl->load('form');
        $h_transl->load('form/_');
        

        
        $form_manager = new Hosh_Manager_Form();
        $listkinds = $form_manager->getKinds();
        $user = Hosh_Manager_User_Auth::getInstance();
        $kinds = array();
        foreach ($listkinds as $val) {
            $flag = true;
            if (! empty($val['acl_value'])) {
                $flag = $user->isAllowed($val['acl_value']);
            }
            if ($flag) {
                $kinds[] = $val['id'];
            }
        }
        $filter = array();
        if (count($listkinds) != count($kinds)) {
            $filter['idkind'] = $kinds;
        }
        if (isset($param['search'])) {
            $filter['sname'] = $param['search'];
        }
        if (! isset($param['page']) or $param['page'] < 1) {
            $param['page'] = 1;
        }
        $offset = ($param['page'] - 1) * $this->countList;
        
        $list = $form_manager->getList($filter, $this->countList, $offset);
        $totalcount = (int) ($form_manager->getTotal($filter));
        foreach ($list as $key => $val) {
            $list[$key]['href'] = $this->view->Url(
                    array(
                            'action' => 'view',
                            'id' => $val['id'],
                            'page' => $param['page'],
                            'search' => $param['search']
                    ));
            $list[$key]['scaption'] = '# ' . $val['id'] . ' ' . $translate->_($val['scaption']);
            $list[$key]['task'] = $this->_getTaskAction($val);
        }
        $paginator = $this->_getPagination($totalcount, $param['page'],$this->countList);
        if ($paginator) {
            $this->view->paginator = $paginator->run();
        }
        return $list;
    }


    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_setStateObject($id, Hosh_Manager_STATE::STATE_DELETE, Hosh_Manager_Form::CLASSNAME, $this->acl_value_delete);
    }

    public function restoreAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_setStateObject($id, Hosh_Manager_STATE::STATE_NORMAL, Hosh_Manager_Form::CLASSNAME, $this->acl_value_restore);
    }

    public function removeAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_removeObject($id, Hosh_Manager_Form::CLASSNAME, $this->acl_value_remove);
    }
    
    
}