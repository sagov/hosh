<?php

require_once 'Zend/View/Helper/HtmlElement.php';

class Hoshmanager_View_Helper_Url extends Zend_View_Helper_HtmlElement
{
    public function Url(array $param = array(),$defaultparam = true)
    {
        $view = Hosh_View::getInstance();
        $url = $view->Hosh_Url($param,$defaultparam);
        return $url;
    }
}