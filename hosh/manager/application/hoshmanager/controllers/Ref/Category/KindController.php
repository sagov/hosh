<?php
require_once dirName(__FILE__) . '/../Abstract.php';

class Hoshmanager_Ref_Category_KindController extends Hoshmanager_Ref_Abstract
{

    protected $idform = 'system_categorykind';

    protected $acl_value = 'HOSH_SYSTEM_CATEGORY_TYPE';

    /**
     *
     * @return Hoshmanager_Ref_Ctg_KindController
     */
    protected function setModContent ()
    {
        
        $id = $this->getRequest()->getParam('id', null);
        $page = $this->getRequest()->getParam('page', 1);
        
        $h_transl = Hosh_Translate::getInstance();
        $h_transl->load('form/' . $this->idform);
        $translate = $h_transl->getTranslate();
        
        $controllers = $this->_getContentControllers(
                array(
                        'addbutton' => array(
                                'link' => $this->view->Url(),
                                'scaption' => $translate->_(
                                        'HOSH_SYS_CTGKIND_NEW'),
                                'acl' => 'HOSH_SYSTEM_CLASS_ADD'
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
    
    /*
     * (non-PHPdoc) @see Hoshmanager_Ref_Abstract::getList()
     */
    protected function getList ($param)
    {
        if (! isset($param['page']) or $param['page'] < 1) {
            $param['page'] = 1;
        }
        $offset = ($param['page'] - 1) * $this->countList;
        $manager_category = new Hosh_Manager_Category();
        $list = $manager_category->getKinds();
        $totalcount = (int) (count($list));
        if (count($totalcount) == 0) {
            $list = array();
        } else {
            $list = array_slice($list, $offset, $this->countList);
        }
        
        foreach ($list as $key => $val) {
            $list[$key]['href'] = $this->view->Url(
                    array(
                            'action' => 'view',
                            'id' => $val['id'],
                            'page' => $param['page'],
                            
                    ));
            $list[$key]['scaption'] = '# ' . $val['id'] . ' ' . $val['scaption'];
        }
        
        $paginator = $this->_getPagination($totalcount, $param['page'], 
                $this->countList);
        if ($paginator) {
            $this->view->paginator = $paginator->run();
        }
        return $list;
    }
}