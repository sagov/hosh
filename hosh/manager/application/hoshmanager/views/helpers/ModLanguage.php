<?php
require_once 'Zend/View/Helper/HtmlElement.php';

class Hoshmanager_View_Helper_ModLanguage extends Zend_View_Helper_HtmlElement
{

    public function ModLanguage (array $param = array())
    {
        $lang = Hosh_Manager_Language_Self::getInstance();
        $list = $lang->getAdapter()->getListAccess();
        $squery_url = $_SERVER['QUERY_STRING'];
        $aquery_url = explode('&', str_replace('&amp;', '&', $squery_url));
        $aurl = array();
        foreach ($aquery_url as $val) {
            $arr = explode('=', $val);
            if (isset($arr[0]) and (isset($arr[1]))) {
                $aurl[$arr[0]] = $arr[1];
            }
        }
        $view = Hosh_View::getInstance();
        $xhtml = null;
        $xhtml .= '
                <div class="dropdown pull-right">
                <a data-toggle="dropdown" href="#"><span class="fa-stack fa-lg">
                      <i class="fa fa-circle fa-stack-2x"></i>
                      <span class="fa fa-stack-1x fa-inverse" style="font-size:11px;font-family:inherit">' .
                 strtoupper($lang->getCode()) .
                 '</span>
                </span></a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-modlanguage" role="menu" aria-labelledby="dLabel">';
        foreach ($list as $key => $val) {            
            $aurl['lang'] = $val['sname'];
            $xhtml .= '<li><a href="' . $view->Hosh_Url($aurl) . '">' .
                     $val['scaption'] . '</a></li>';
        }
        $xhtml .= '</ul>
                </div>
                 ';
        return $xhtml;
    }
}    