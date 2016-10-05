<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Inputgroup.php 21.04.2016 18:19:10
 */
require_once 'Zend/Form/Decorator/HtmlTag.php';

/**
 * Decorator form Bootstrap_Inputgroup
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Decorator
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Decorator_Bootstrap_Inputgroup extends Zend_Form_Decorator_HtmlTag
{
    /*
     * (non-PHPdoc) @see Zend_Form_Decorator_HtmlTag::render()
     */
    public function render ($content)
    {
        $text = $this->getOption('text');
        $addtext = array();
        if (is_array($text)) {
            foreach ($text as $val) {
                switch (strtoupper($val['placement'])) {
                    case 'PREPEND':
                        $addtext['prepend'] = $val['content'];
                        break;
                    
                    default:
                        $addtext['append'] = $val['content'];
                        break;
                }
            }
        } else {
            $addtext['append'] = $text;
        }
        
        $xhtml = null;
        $xhtml .= '<div class="input-group">';
        if (isset($addtext['prepend'])) {
            $xhtml .= $addtext['prepend'];
        }
        $xhtml .= $content;
        if (isset($addtext['append'])) {
            $xhtml .= $addtext['append'];
        }
        $xhtml .= '</div>';
        
        return $xhtml;
    }
}