<?php

require_once dirName(__FILE__) . '/Abstract.php';

class Hoshmanager_CabinetController extends Hoshmanager_Abstract
{

    public function indexAction()
    {
        $menu = HoshManager_Model_Menu::getInstance();
        $menu = $menu->getMenu();
        $this->view->menulist = $menu;
        $h_transl = Hosh_Translate::getInstance();
        $translate = $h_transl->getTranslate();
        $this->view->HeadTitle($translate->_('HM_CABINET'));
        $userauth = Hosh_Manager_User_Auth::getInstance();
        $this->view->loglist = $userauth->getLogList(7);


        $items_str = array(
            array('sname' => 'title', 'scaption' => 'Название'),
            array('sname' => 'key', 'scaption' => 'Код'),
            array('sname' => 'state', 'scaption' => 'Статус'),
            array('sname' => 'public', 'scaption' => 'Публикация'),
            array('sname' => 'price', 'scaption' => 'Стоимость'),
            array('sname' => 'scomment', 'scaption' => 'Комментарий','type'=>'Textarea'),
        );

        $data = array(
            array('title' => 'Название1', 'key' => 'Код1', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>200),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
            array('title' => 'Название2', 'key' => 'Код2', 'state' => 'Черновик', 'public' => 'Опубликован','price'=>500),
        );


        // Таблица стандартная
        $list = new Hosh_List();

        $list->addItem('head');
        $head_item = $list->getItem('head');
        $head_item->addDecorator('ListElements');
        $head_item->addDecorator('HtmlTag', array('tag' => 'tr'));
        foreach ($items_str as $val) {
            $head_item->addElement('Value', $val['sname']);
            $element = $head_item->getElement($val['sname']);
            $element->setValue($val['scaption']);
            $element->addDecorator('HtmlTag', array('tag' => 'th'));
        }

        foreach ($data as $key => $val) {
            $list->addItem($key);
            $item = $list->getItem($key);
            $item->addDecorator('ListElements');
            $item->addDecorator('HtmlTag', array('tag' => 'tr'));
            foreach ($items_str as $val1) {
                $value = (isset($val[$val1['sname']])) ? $val[$val1['sname']] : '';
                $type = (isset($val1['type'])) ? $val1['type'] : 'Value';
                $item->addElement($type, $val1['sname']);
                $element = $item->getElement($val1['sname']);
                $element->setPrefName($key.'_');
                $element->setValue($value);
                $element->addDecorator('HtmlTag', array('tag' => 'td'));

            }

        }
        $list->addDecorator('ListItems');
        $list->addDecorator('HtmlTag', array('tag' => 'table', 'class' => 'table'));
        echo $list->render();


        // Блоки
        $list = new Hosh_List();

        foreach ($data as $key => $val) {
            $list->addItem($key);
            $item = $list->getItem($key);
            $item->addDecorator('ListElements');
            $item->addDecorator('HtmlTag1', array('tag' => 'div','style'=>'border:1px solid #f0f0f0;padding:20px;margin-bottom:20px'));
            $item->addDecorator('HtmlTag', array('tag' => 'div','class'=>'col-sm-4'));

            foreach ($items_str as $val1) {
                $value = (isset($val[$val1['sname']])) ? $val[$val1['sname']] : '';
                $type = (isset($val1['type'])) ? $val1['type'] : 'Value';
                $item->addElement($type, $val1['sname']);
                $element = $item->getElement($val1['sname']);
                $element->setPrefName($key.'_');
                $element->setValue($value);
                $element->addDecorator('Layout', array('prepend' => $val1['scaption'].': '));
                $element->addDecorator('HtmlTag', array('tag' => 'div'));

            }

        }
        $list->addDecorator('ListItems');
        $list->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'row'));
        echo $list->render();


        // Блоки + View Element
        $list = new Hosh_List();
        $list->setView(Hosh_View::getInstance());
        foreach ($data as $key => $val) {
            $list->addItem($key);
            $item = $list->getItem($key);
            $item->addDecorator('ViewElements');
            $item->addDecorator('HtmlTag1', array('tag' => 'div','style'=>'border:1px solid #f0f0f0;padding:20px;margin-bottom:20px'));
            $item->addDecorator('HtmlTag', array('tag' => 'div','class'=>'col-sm-4'));

            foreach ($items_str as $val1) {
                $value = (isset($val[$val1['sname']])) ? $val[$val1['sname']] : '';
                $type = (isset($val1['type'])) ? $val1['type'] : 'Value';
                $item->addElement($type,  $val1['sname']);
                $element = $item->getElement( $val1['sname']);
                $element->setPrefName($key.'_');
                $element->setValue($value);
            }

        }
        $list->addDecorator('ListItems');
        $list->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'row'));
        echo $list->render();

    }


}