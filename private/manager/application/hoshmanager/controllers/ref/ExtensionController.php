<?php
/**
 * Hosh Manager
 *
 * @category    HoshManager
 * @package     Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: ExtensionController.php 14.04.2016 12:51:02
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
class Hoshmanager_Ref_ExtensionController extends Hoshmanager_Ref_Abstract
{

    /**
     * @var unknown
     */
    protected $idform = 'system_extension';

    /**
     * @var unknown
     */
    protected $acl_value = 'HOSH_SYSTEM_EXTENSION_REF';

    /**
     *
     * @return Hoshmanager_Ref_ExtensionController
     */
    protected function setModContent ()
    {
        $search = $this->getRequest()->getParam('search', null);
        $page = $this->getRequest()->getParam('page', 1);
        $id = $this->getRequest()->getParam('id', null);
        
        $h_transl = Hosh_Translate::getInstance();               
        $h_transl->load('form/system_extension');
        $translate = $h_transl->getTranslate();
        
        $controllers = $this->_getContentControllers(
                
                array(
                        'addbutton' => array(
                                'link' => $this->view->Url(),
                                'scaption' => $translate->_('HOSH_SYS_NEW_EXTENSION'),
                                'acl' => 'HOSH_SYSTEM_EXTENSION_ADD'
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
        $m_extension = new Hosh_Manager_Extension();
        $filter = array();
        
        if (!empty($param['search'])) {
            $filter['sname'] = $param['search'];
        }
        if (! isset($param['page']) or $param['page'] < 1) {
            $param['page'] = 1;
        }
        $offset = ($param['page'] - 1) * $this->countList;
        
        $list = $m_extension->getList($filter, $this->countList, $offset);
        $totalcount = (int) ($m_extension->getTotal($filter));
        foreach ($list as $key => $val) {
            $list[$key]['href'] = $this->view->Url(
                    array(
                            'action' => 'view',
                            'id' => $val['id'],
                            'page' => $param['page'],
                            'search' => $param['search'],
                    ));
            $list[$key]['scaption'] = '# ' . $val['id'] . ' ' . $val['sname'];
        }
        $paginator = $this->_getPagination($totalcount, $param['page'],$this->countList);
        if ($paginator) {
            $this->view->paginator = $paginator->run();
        }
        return $list;
    }
}