<?php
/**
 * Hosh Manager
 *
 * @category    HoshManager
 * @package     Controller
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: LanguageController.php 14.04.2016 12:51:02
 */
require_once 'Abstract.php';
/**
 * Language Controller
 *
 * @category    HoshManager
 * @package     Controller
 * @author      Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright   Copyright (c) 2016 Hosh
 *
 */
class Hoshmanager_Ref_LanguageController extends Hoshmanager_Ref_Abstract
{

    /**
     * @var unknown
     */
    protected $idform = 'system_language';

    /**
     * @var unknown
     */
    protected $acl_value = 'HOSH_SYSTEM_LANGUAGE_REF';

    /**
     *
     * @return Hoshmanager_Ref_LanguageController
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
                                'scaption' => $translate->_('HOSH_SYS_NEW_LANG'),
                                'acl' => 'HOSH_SYSTEM_LANGUAGE_ADD'
                        )
                ));
        
       
        
        $list = $this->getList(
                array(                        
                        'page' => $page
                ));
        $menu = $this->_getContentMenu($list, array(
                'idactive' => $id
        ));
        
        $this->_setModContent(implode('', 
                array(
                        $controllers,                        
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
        $m_lang = new Hosh_Manager_Language();
        
        $list = $m_lang->getAdapter()->getList();
        $totalcount = (int) (count($list));
        if (count($totalcount) == 0){
            $list = array();
        }else{
            $list = array_slice($list, $offset, $this->countList);
        }
        foreach ($list as $key => $val) {
            $list[$key]['href'] = $this->view->Url(
                    array(
                            'action' => 'view',
                            'id' => $val['id'],
                            'page' => $param['page'],                            
                    ));
            $list[$key]['scaption'] = '# ' . $val['sname'] . ' ' . $val['scaption'];
        } 
        $paginator = $this->_getPagination($totalcount, $param['page'],$this->countList);
        if ($paginator) {
            $this->view->paginator = $paginator->run();
        }      
        return $list;
    }
}