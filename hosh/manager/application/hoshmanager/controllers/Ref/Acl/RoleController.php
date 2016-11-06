<?php
/**
 * Hosh Manager
 *
 * @category    HoshManager
 * @package     Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: RoleController.php 14.04.2016 12:51:02
 */
require_once dirName(__FILE__) . '/../Abstract.php';

/**
 * Description of file_name
 *
 * @category HoshManager
 * @package Controller
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hoshmanager_Ref_Acl_RoleController extends Hoshmanager_Ref_Abstract
{

    /**
     * @var unknown
     */
    protected $idform = 'system_acl_role';

    /**
     * @var unknown
     */
    protected $acl_value = 'HOSH_SYSTEM_ACL_ROLE_REF';

    /**
     *
     * @return Hoshmanager_Ref_Acl_RoleController
     */
    protected function setModContent ()
    {
        $id = $this->getRequest()->getParam('id', null);
        $page = $this->getRequest()->getParam('page', 1);
        
        $h_transl = Hosh_Translate::getInstance();
        $h_transl->load('form/'.$this->idform);
        $translate = $h_transl->getTranslate();
        
        $controllers = $this->_getContentControllers(
                
                array(
                        'addbutton' => array(
                                'link' => $this->view->Url(),
                                'scaption' => $translate->_('HOSH_SYS_ACLR_NEW_ROLE'),
                                'acl' => 'HOSH_SYSTEM_ACL_ROLE_ADD'
                        )
                ));
        
        $list = $this->getList(
                array(
                        'page' => $page
                ));
        $menu = $this->_getContentMenu($list, array(
                'idactive' => $id
        ));
        
        $this->_setModContent(implode('', array(
                $controllers,
                $menu
        )));
        return $this;
    }

    /* (non-PHPdoc)
     * @see Hoshmanager_Ref_Abstract::getList()
     */
    protected function getList ($param = null)
    {
        $h_transl = Hosh_Translate::getInstance();
        $h_transl->load('manager/_');
        $translate = $h_transl->getTranslate();
        $m_aclrole = new Hosh_Manager_Acl_Role();
        
        if (! isset($param['page']) or $param['page'] < 1) {
            $param['page'] = 1;
        }
        $offset = ($param['page'] - 1) * $this->countList;
        $list = $m_aclrole->getList();
        $applist = Hosh_Application_List::getInstance();
        $list_treetotal = $applist->toTree($list);
        $list_tree = array_slice($list_treetotal, $offset, $this->countList);
        foreach ($list_tree as $key => $val) {
            $list_tree[$key]['href'] = $this->view->Url(
                    array(
                            'action' => 'view',
                            'id' => $val['id'],
                            'page' => $param['page']
                    ));
            $list_tree[$key]['scaption'] = $applist->getLevelCaption(
                    $val['level']) . '# ' . $val['id'] . ' ' . $translate->_($val['scaption']);
        }
        $paginator = $this->_getPagination(count($list_treetotal), 
                $param['page'], $this->countList);
        if ($paginator) {
            $this->view->paginator = $paginator->run();
        }
        return $list_tree;
    }
}