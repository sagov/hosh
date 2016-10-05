<?php

require_once 'Zend/View/Helper/HtmlElement.php';

class Hoshmanager_View_Helper_Ref_Url extends Zend_View_Helper_HtmlElement
{
    public function Ref_Url($row)
    {
        $view = Hosh_View::getInstance();
        switch (strtoupper($row['snameclass']))
        {
            case 'FORM':
            case 'EXTENSION':
            case 'ACL_VALUE':
            case 'ACL_ROLE':
            case 'USER':
            case 'CATEGORY':
            return  $view->Hosh_Url(array('controller'=>'ref_'.strtolower($row['snameclass']),'action'=>'view','id'=>$row['idobject']));
                break;
                
            default:
                break;    
        }
        return null;
    }
}    