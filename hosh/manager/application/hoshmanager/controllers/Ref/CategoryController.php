<?php

require_once 'Abstract.php';

class Hoshmanager_Ref_CategoryController extends Hoshmanager_Ref_Abstract
{
    protected $idform = 'system_category';

    protected $acl_value = 'HOSH_SYSTEM_CATEGORY_REF';

    protected $acl_value_add = 'HOSH_SYSTEM_CATEGORY_ADD';

    protected $acl_value_remove = 'HOSH_SYSTEM_CATEGORY_REMOVE';

    protected $acl_value_delete = 'HOSH_SYSTEM_CATEGORY_DELETE';

    protected $acl_value_restore = 'HOSH_SYSTEM_CATEGORY_RESTORE';

    /**
     * @return Hoshmanager_Ref_CategoryController
     */
    protected function setModContent()
    {
        $id = $this->getRequest()->getParam('id', null);
        $page = $this->getRequest()->getParam('page', 1);

        $h_transl = Hosh_Translate::getInstance();
        $h_transl->load('form/' . $this->idform);
        $translate = $h_transl->getTranslate();

        $controllers = $this->_getContentControllers(
            array('addbutton' => array(
                'link' => $this->view->Url(),
                'scaption' => $translate->_('HOSH_SYS_CTG_NEW'),
                'acl' => $this->acl_value_add
            ))
        );

        $manager_category = new Hosh_Manager_Category();
        $kinds = $manager_category->getKinds();
        $arrkinds = array();

        $s = Hosh_Session::getInstanse();
        $search = $s->get('ref.category.search');


        foreach ($kinds as $val) {
            if (empty($search)) {
                $search = $val['sname'];
            }
            $arrkinds[$val['sname']] = $val['scaption'];
        }
        $search = $this->getRequest()->getParam('search', $search);


        $filtermenu = $this->_getContentFilter(
            array(
                'search_url' => $this->view->Url(
                    array(
                        'action' => 'search'
                    )),
                'list' => $arrkinds,
                'selected' => $search,
            )
            , 'ref/filter/select.phtml');

        $list = $this->getList(array(
            'search' => $search,
            'page' => $page
        ));
        $menu = $this->_getContentMenu($list, array('idactive' => $id));

        $this->_setModContent(implode('', array($controllers, $filtermenu, $menu)));
        return $this;
    }

    protected function getList($param)
    {
        if (!isset($param['page']) or $param['page'] < 1) {
            $param['page'] = 1;
        }
        $offset = ($param['page'] - 1) * $this->countList;
        $filter = array();
        $filter['skindname'] = $param['search'];
        if ($param['search']) {
            $s = Hosh_Session::getInstanse();
            $s->set('ref.category.search', $param['search']);
        }
        $manager_category = new Hosh_Manager_Category();
        $list = $manager_category->getList($filter);
        $applist = Hosh_Application_List::getInstance();
        $list_treetotal = $applist->toTree($list);

        if (count($list_treetotal) == 0) {
            $list_tree = array();
        } else {
            $list_tree = array_slice($list_treetotal, $offset, $this->countList);
        }
        $parent = array();
        if (is_array($list_treetotal)) {
            foreach ($list_treetotal as $val) {
                $parent[$val['idparent']][] = $val['id'];
            }
        }
        foreach ($list_tree as $key => $val) {
            $list_tree[$key]['href'] = $this->view->Url(
                array(
                    'action' => 'view',
                    'id' => $val['id'],
                    'page' => $param['page'],

                ));
            $list_tree[$key]['scaption'] = $applist->getLevelCaption($val['level']) . $val['scaption'];
            $list_tree[$key]['task'] = $this->_getTaskAction($val);
            if (isset($list_tree[$key]['task']['remove']) and isset($parent[$val['id']])) {
                unset($list_tree[$key]['task']['remove']);
            }
        }
        $paginator = $this->_getPagination(count($list_treetotal),
            $param['page'], $this->countList);
        if ($paginator) {
            $this->view->paginator = $paginator->run();
        }
        return $list_tree;
    }


    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_setStateObject($id, Hosh_Manager_State::STATE_DELETE, Hosh_Manager_Category::CLASSNAME, $this->acl_value_delete);
    }

    public function restoreAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_setStateObject($id, Hosh_Manager_State::STATE_NORMAL, Hosh_Manager_Category::CLASSNAME, $this->acl_value_restore);
    }

    public function removeAction()
    {
        $id = $this->getRequest()->getParam('target', null);
        $this->_removeObject($id, Hosh_Manager_Category::CLASSNAME, $this->acl_value_remove);
    }

}