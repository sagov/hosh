<?php
/**
 * Hosh Manager
 *
 * @category    HoshManager
 * @package     Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: UserController.php 14.04.2016 12:51:02
 */
require_once 'Abstract.php';

/**
 * User Controller
 *
 * @category    HoshManager
 * @package     Controller
 * @author      Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright   Copyright (c) 2016 Hosh
 *
 */
class Hoshmanager_Ref_UserController extends Hoshmanager_Ref_Abstract
{

    /**
     * @var unknown
     */
    protected $idform = 'system_user';
    /**
     * @var unknown
     */
    protected $acl_value = 'HOSH_SYSTEM_USER_REF';

    protected $acl_value_add = 'HOSH_SYSTEM_USER_ADD';

    protected $acl_value_remove = 'HOSH_SYSTEM_USER_REMOVE';

    protected $acl_value_delete = 'HOSH_SYSTEM_USER_DELETE';

    protected $acl_value_restore = 'HOSH_SYSTEM_USER_RESTORE';

    /* (non-PHPdoc)
     * @see Hoshmanager_Ref_Abstract::viewAction()
     */
    public function viewAction()
    {
        parent::viewAction();
    }


    /**
     * @return Hoshmanager_Ref_StateController
     */
    protected function setModContent()
    {
        $search = $this->getRequest()->getParam('search', null);
        $page = $this->getRequest()->getParam('page', 1);
        $id = $this->getRequest()->getParam('id', null);

        $h_transl = Hosh_Translate::getInstance();
        $h_transl->load('form/' . $this->idform);
        $translate = $h_transl->getTranslate();

        $controllers = $this->_getContentControllers(

            array('addbutton' => array(
                'link' => $this->view->Url(),
                'scaption' => $translate->_('HOSH_SYS_NEW_USER'),
                'acl' => $this->acl_value_add
            ))
        );

        $filtermenu = $this->_getContentFilter(
            array(
                'search_url' => $this->view->Url(
                    array(
                        'action' => 'search'
                    )),
                'search_placeholder' => $translate->_('HOSH_SYS_ENTER_LOGIN'),
            )
        );

        $list = $this->getList(
            array(
                'search' => $search,
                'page' => $page
            ));
        $menu = $this->_getContentMenu($list, array('idactive' => $id));

        $this->_setModContent(implode('', array($controllers, $filtermenu, $menu)));
        return $this;
    }

    /* (non-PHPdoc)
     * @see Hoshmanager_Ref_Abstract::getList()
     */
    protected function getList($param = null)
    {

        $config = Hosh_Config::getInstance();
        $adaptername = $config->get('adapter', 'default');

        $m_user = new Hosh_Manager_User();
        $adapter_user = $m_user->getAdapter();
        $filter = array();
        if (!empty($param['search'])) {
            $filter['search'] = $param['search'];
        }
        if (!isset($param['page']) or $param['page'] < 1) {
            $param['page'] = 1;
        }
        $offset = ($param['page'] - 1) * $this->countList;
        $list = $adapter_user->getList($filter, $this->countList, $offset);
        $totalcount = (int)($adapter_user->getTotal($filter));
        foreach ($list as $key => $val) {
            $list[$key]['href'] = $this->view->Url(
                array(
                    'action' => 'view',
                    'id' => $val['id'],
                    'page' => $param['page'],
                    'search' => $param['search']
                ));
            $list[$key]['scaption'] = '# ' . $val['id'] . ' ' . $val['susername'];
            if (strtolower($adaptername) == 'default') {
                $list[$key]['task'] = $this->_getTaskAction($val);
            }
        }
        $paginator = $this->_getPagination($totalcount, $param['page'], $this->countList);
        if ($paginator) {
            $this->view->paginator = $paginator->run();
        }
        return $list;
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_setStateObject($id, Hosh_Manager_STATE::STATE_DELETE, Hosh_Manager_User::CLASSNAME, $this->acl_value_delete);
    }

    public function restoreAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_setStateObject($id, Hosh_Manager_STATE::STATE_NORMAL, Hosh_Manager_User::CLASSNAME, $this->acl_value_restore);
    }

    public function removeAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_removeObject($id, Hosh_Manager_User::CLASSNAME, $this->acl_value_remove);
    }
}