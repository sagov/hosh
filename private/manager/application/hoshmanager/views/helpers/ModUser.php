<?php
require_once 'Zend/View/Helper/HtmlElement.php';

class Hoshmanager_View_Helper_ModUser extends Zend_View_Helper_HtmlElement
{

    public function ModUser (array $param = array())
    {
        $user = Hosh_Manager_User_Auth::getInstance();
        if ($user->isExist()) {
            return $this->_getAuthUser();
        } 
        return;
    }

    protected function _getAuthUser ()
    {
        $user = Hosh_Manager_User_Auth::getInstance();
        $h_translate = Hosh_Translate::getInstance();
        $translate = $h_translate->getTranslate();
        $data = $user->getData();   
        $view = Hosh_View::getInstance();
        $xhtml = '';
        $xhtml .= '                
                <div class="dropdown pull-right">
                <a data-toggle="dropdown" href="#"><span class="fa-stack fa-lg">
                      <i class="fa fa-circle fa-stack-2x"></i>
                      <i class="fa fa-user fa-stack-1x fa-inverse"></i>
                    </span></a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-moduser" role="menu" aria-labelledby="dLabel">
                <li class="scaption">'.$data['susername'].'</li>                
                <li class="divider"></li>        
                <li class="exit"><a href="'.$view->Hosh_Url(array('controller'=>'auth','action'=>'logout')).'" class="btn btn-default">'.$translate->_('HM_EXIT').'</a></li>               
                </ul>
                </div>
                 ';
        return $xhtml;
    }

   
}    