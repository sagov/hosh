<?php
require_once 'Zend/View/Helper/HtmlElement.php';

class Hosh_View_Helper_Hosh_Url extends Zend_View_Helper_HtmlElement
{

    public function Hosh_Url ($param, $defaultparam = true)
    {
        if (is_string($param))
        {
            $param = $this->_getArrayParamUrlString($param);
        }
        $urlparam = $this->_getUrlRequest($param, $defaultparam);
        $config = Hosh_Config::getInstance();
        $url = $config->get('route') . '?' . implode('&', $urlparam);
        return $url;
    }

    protected function _getArrayParamUrlString ($url_string)
    {
        $url_string = str_replace('?', null, $url_string);
        $url_astring = explode('&', $url_string);
        $aurl = array();
        foreach ($url_astring as $val) {
            $_astring = explode('=', $val);
            $aurl[$_astring[0]] = $_astring[1];
        }
        return $aurl;
    }

    protected function _getUrlRequest ($param, $defaultparam = true)
    {
        if ($defaultparam) {
            $controller_param = array();
            $_controller = Zend_Controller_Front::getInstance();
            $controller_param['controller'] = $_controller->getDefaultControllerName();
            $controller_param['action'] = $_controller->getDefaultAction();
            $param = array_merge($controller_param, $param);
        }
        
        $urlparam = array();
        foreach ($param as $key => $val) {
            if (isset($val)) {
                $urlparam[] = $key . '=' . $val;
            }
        }
        return $urlparam;
    }
}	