<?php

class Hoshmanager_FormController extends Zend_Controller_Action
{

    protected $options = array(
            'decoratorHelper' => 'Decorator_Form_Bootstrap',
            'decoratorElementHelper' => 'Decorator_Element_Bootstrap'
    );

    public function emptylayoutAction ()
    {
        $this->_helper->layout->setLayout('empty');
        $this->forward('view');
    }

    public function indexAction ()
    {
        $this->forward('view');
    }

    public function viewAction ()
    {
        $form = $this->_getForm();
        $form->run();
        
        if ($form->isRequest()) {
            if (! $form->setRequest()) {
                $view = Hosh_View::getInstance();
                $message_error = $view->Hosh_Form_MessageErrors($form);
                if (! empty($message_error)) {
                    $alert = Hosh_Controller_Action_Helper_Alert::getInstance();
                    $alert->add($message_error, 'danger');
                }
            } else {
                $this->view->successfully = true;
            }
        }
        
        // submit button
        $submit_param = $this->getParam('submit', null);
        if (isset($submit_param)) {
            if (! is_array($submit_param)) {
                $submit_param = array();
            }
            if (empty($submit_param['label'])) {
                $h_transl = Hosh_Translate::getInstance();
                $translate = $h_transl->getTranslate();
                $adapter_transl = $translate->getAdapter();
                $h_transl->load('form/_');
                $submit_param['label'] = $adapter_transl->_('SYS_SAVE');
            }
            if (empty($submit_param['name'])) {
                $submit_param['name'] = 'save';
            }
            
            $action = array(
                    array(
                            'type' => 'submit',
                            'label' => $submit_param['label'],
                            'name' => $submit_param['name']
                    ),
            );
            $form->getHelper('Hosh_AddAction', array('actions'=>$action));
            
            
        }
        
        $title_param = $this->getParam('title', null);
        $meta = $form->getHelperPattern('meta');
        if (! empty($meta['title'])) {
            $this->view->headTitle($meta['title']);
        }
        $this->view->meta = $meta;
        $this->view->form = $form;
    }

    public function viewhelperAction ()
    {
        $name = $this->getRequest()->getParam('name', null);
        $value = $this->getRequest()->getParam('value', null);
        
        $this->_helper->layout->disableLayout();
        $this->options['is_layout'] = false;
        $form = $this->_getForm();
        $pattern = $form->getPattern();
        $elements = $pattern->getElements();
        $newelpattern = array();
        if ($form->isEdit()) {
            $form->loadData();
        }
        if (isset($elements[$name])) {
            $elements[$name]->set('defaultvalue', null);
            $newelpattern[$name] = $elements[$name];
            $form->setData($name, null);
        }
        $pattern->setElements($newelpattern);
        
        $form->getHelper('Hosh_AddPatternElements', 
                array(
                        array(
                                'name' => $name,
                                'value' => $value
                        )
                ));
        $elements = $pattern->getElements();
        if (isset($elements[$name])){
            unset($elements[$name]);
        }
        $pattern->setElements($elements);
        $form->run();
        $elements = $form->getElements();
        $xhtml = array();
        $pattern = $form->getPattern();
        $elements_pattern = $pattern->getElements();
        foreach ($elements_pattern as $key => $val) {
            $val_data = $val->getData();
            if ((isset($val_data['parent']) and $val_data['parent'] == $name)) {
                if ($element = $form->getElement($key)) {
                    $element->setTranslator($form->getTranslator());
                    $xhtml['element'][$key] = $element->render();
                }
            }
        }

        if (count($xhtml) >0) {
            $view = $form->getView();
            $view->JQuery(false)->remove();
            $view->Bootstrap(false)->remove();
            $xhtml['js'] = $view->headScript()->toString();
            $xhtml['css'] = $view->headLink()->toString();
        }
        $this->view->xhtml = Zend_Json::encode($xhtml);
        $this->_helper->layout->disableLayout();
        $this->render('task');
        return;
    }

    public function gethelpersAction ()
    {
        $this->_helper->layout->disableLayout();
        $this->options['is_layout'] = false;
        $form = $this->_getForm();
        $list = array();
        if ($form) {
            $pluginsetting = $form->getPlugin();
            $dir = new Hosh_Dir();
            $listdir = $dir->getListScan(
                    $pluginsetting['path'] . '/' . $form::HELPER . '/', 
                    array(
                            'isdir' => true,
                            'ex_name' => array(
                                    '.',
                                    '..'
                            )
                    ));
            $listhelpers = $dir->getListScan(
                    $pluginsetting['path'] . '/' . $form::HELPER . '/', 
                    array(
                            'isdir' => false,
                            'ext' => '.php'
                    ));
            if (is_array($listhelpers)) {
                foreach ($listhelpers as $helper) {
                    $list['self'][] = str_replace('.php', null, $helper);
                }
            }
            if (is_array($listdir)) {
                foreach ($listdir as $dirvalue) {
                    $listhelpers2 = $dir->getListScan(
                            $pluginsetting['path'] . '/' . $form::HELPER . '/' .
                                     $dirvalue . '/', 
                                    array(
                                            'isdir' => false,
                                            'ext' => '.php'
                                    ));
                    foreach ($listhelpers2 as $helper) {
                        $list['self'][] = $dirvalue . '_' .
                                 str_replace('.php', null, $helper);
                    }
                }
            }
        }
        $packext = new Hosh_Manager_Db_Package_Hosh_Extension();
        $listext = $packext->getList(
                array(
                        'snamekind' => 'FORM_HELPER',
                        'iscategory' => true
                ));
        foreach ($listext as $val) {
            $list[$val['idcategory']][] = $val['sname'];
        }
        $this->view->xhtml = Zend_Json::encode($list);
        $this->render('task');
        return;
    }

    public function getelementsAction ()
    {
        $this->_helper->layout->disableLayout();
        $this->options['is_layout'] = false;
        
        $listcategory = $list = array();
        
        $hcategory = new Hosh_Manager_Category();
        $listcategory_data = $hcategory->getList(
                array(
                        'skindname' => 'FORM_ELEMENT'
                ));
        foreach ($listcategory_data as $val) {
            $listcategory[$val['id']] = $val;
        }
        $this->view->listcategory = $listcategory;
        
        $packext = new Hosh_Manager_Db_Package_Hosh_Extension();
        $listext = $packext->getList(
                array(
                        'snamekind' => 'FORM_ELEMENT',
                        'iscategory' => true
                ));
        foreach ($listext as $val) {
            $list[$val['idcategory']][] = $val;
        }
        $this->view->list = $list;
    }

    public function taskAction ()
    {
        $this->_helper->layout->disableLayout();
        $form = $this->_getForm();
        $form->loadData();
        $task = $this->getRequest()->getParam('task', null);
        if (! empty($task)) {
            $format = $this->getRequest()->getParam('format', null);
            $result = null;
            try {
                $result['result'] = $form->getHelper($task);
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            switch (strtolower($format)) {
                case 'json':
                    $this->view->xhtml = Zend_Json::encode($result);
                    break;
                
                default:
                    $this->view->xhtml = $result;
                    break;
            }
        }
    }

    /**
     *
     * @return Hosh_Form
     */
    protected function _getForm ()
    {
        $idform = $this->getParam('idform', 
                $this->getRequest()
                    ->getParam('idform', null));
        $id = $this->getRequest()->getParam('id', null);
        
        if (empty($idform)) {
            return false;
        }
        
        require_once 'Hosh/Form.php';
        
        if (isset($id)) {
            $this->options['updateparams'] = array(
                    'id' => $id
            );
        }
        
        $form = new Hosh_Form($idform, $this->options);
        $form->initialize();

        return $form;
    }
}