<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Pane.php 21.04.2016 18:19:10
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
class Hosh_Form_Decorator_Bootstrap_Tab_Pane extends Zend_Form_Decorator_HtmlTag
{
    /*
     * (non-PHPdoc) @see Zend_Form_Decorator_HtmlTag::render()
     */
    public function render ($content)
    {
    	$id = $this->getOption('id');
    	$active = $this->getOption('active');
    	
    	$class = ($active) ? ' active' : null;
    	
    	$xhtml = null;
    	$xhtml .= '<div class="tab-pane'.$class.'" id="'.$id.'">';    	
    	$xhtml .= $content;    	
    	$xhtml .= '</div>';
    	
    	return $xhtml;
    }
}    