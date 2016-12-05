<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Nav.php 21.04.2016 18:19:10
 */
require_once 'Zend/Form/Decorator/HtmlTag.php';

/**
 * Decorator form Bootstrap_Tab_Nav
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Decorator
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Decorator_Bootstrap_Tab_Nav extends Zend_Form_Decorator_Abstract
{
    /*
     * (non-PHPdoc) @see Zend_Form_Decorator_HtmlTag::render()
     */
    public function render ($content)
    {
    	$displaygroups = $this->getOption('displaygroups');
    	
    	$id = $this->getOption('id');
    	
    	$xhtml = null;
    	$xhtml .= '<ul id="myTab" class="nav nav-tabs">';    	
    	foreach ($displaygroups as $key=>$group){
    		$class = ($group['active']) ? 'active' : null;
    		$xhtml .= '<li class="'.$class.'"><a href="#'.$key.'" data-toggle="tab">'.$group['label'].'</a></li>';
    	}   	
    	$xhtml .= '</ul>';    	
    	$xhtml .= $content;
    	return $xhtml;
    }
}    