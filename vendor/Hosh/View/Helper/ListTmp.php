<?php

require_once 'Zend/View/Helper/HtmlElement.php';

class Hosh_View_Helper_ListTmp extends Zend_View_Helper_HtmlElement
{


    public function ListTmp(Hosh_List_Item $item)
    {
        $title = $item->getElement('title');
        $price = $item->getElement('price');
        $scomment = $item->getElement('scomment');

        $xhtml = '<ul>
            <li>'.$title->render().'</li>
            <li>'.$price->render().'</li>
            <li>'.$scomment->render().'</li>
            </ul>';

        return $xhtml;
    }
}