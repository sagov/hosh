<?php
/**
 * Hosh Manager
 *
 * @category    HoshManager  
 * @package     Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: ClassController.php 14.04.2016 12:51:02
 */
require_once 'Abstract.php';
/**
 * Class Controller
 * 
 * @category    HoshManager  
 * @package     Controller
 * @author      Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright   Copyright (c) 2016 Hosh 
 *
 */
class Hoshmanager_Ref_ClassController extends Hoshmanager_Ref_Abstract
{

    protected $idform = 'system_class';

    protected $acl_value = 'HOSH_SYSTEM_CLASS_REF';

    /**
     *
     * @return Hoshmanager_Ref_ClassController
     */
    protected function setModContent ()
    {
        $search = $this->getRequest()->getParam('search', null);
        $id = $this->getRequest()->getParam('id', null);
        $page = $this->getRequest()->getParam('page', 1);
        
        $h_transl = Hosh_Translate::getInstance();               
        $h_transl->load('form/'.$this->idform);
        $translate = $h_transl->getTranslate();
        
        $controllers = $this->_getContentControllers(
                array(
                        'addbutton' => array(
                                'link' => $this->view->Url(),
                                'scaption' => $translate->_('HOSH_SYS_CLASS_NEW'),
                                'acl' => 'HOSH_SYSTEM_CLASS_ADD'
                        )
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
        $menu = $this->_getContentMenu($list, array(
                'idactive' => $id
        ));
        
        $this->_setModContent(implode('', 
                array(
                        $controllers,
                        $filtermenu,
                        $menu
                )));
        return $this;
    }

    /* (non-PHPdoc)
     * @see Hoshmanager_Ref_Abstract::getList()
     */
    protected function getList ($param)
    {
        if (! isset($param['page']) or $param['page'] < 1) {
            $param['page'] = 1;
        }
        $offset = ($param['page'] - 1) * $this->countList;
        $mclass = new Hosh_Manager_Class();
        $filter = array();
        if (isset($param['search'])) {
            $filter['sname'] = $param['search'];
        }
        $list = $mclass->getList($filter, $this->countList, $offset);
        $totalcount = (int) ($mclass->getTotal($filter));
        foreach ($list as $key => $val) {
            $list[$key]['href'] = $this->view->Url(
                    array(
                            'action' => 'view',
                            'id' => $val['id'],
                            'page' => $param['page'],
                            'search' => $param['search']
                    ));
            $list[$key]['scaption'] = '# ' . $val['id'] . ' ' . $val['sname'];
        }
        $modal_ref = new HoshManager_Model_Ref();
        $paginator = $modal_ref->getPagination($totalcount, $param['page']);
        if ($paginator) {
            $this->view->paginator = $paginator->run();
        }
        return $list;
    }
}