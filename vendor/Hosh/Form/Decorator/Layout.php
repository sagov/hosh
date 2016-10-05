<?php

/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Layout.php 21.04.2016 18:19:10
 */
require_once 'Zend/Form/Decorator/HtmlTag.php';

/**
 * Decorator form Layout
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Decorator
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Decorator_Layout extends Zend_Form_Decorator_HtmlTag
{
    /*
     * (non-PHPdoc) @see Zend_Form_Decorator_HtmlTag::render()
     */
    public function render ($content)
    {
        $prepend = $this->getOption('prepend');
        $append = $this->getOption('append');
        
        return $prepend . $content . $append;
    }
}