<?php

require_once 'Zend/View/Helper/HeadLink.php';

class Hosh_View_Helper_AddStyleDeclaration extends Zend_View_Helper_HeadLink
{

    public function AddStyleDeclaration( $text )
    {
        if (empty($text)) {
            return false;
        }

        $this->view->headStyle($text);

        return $this;
    }
}