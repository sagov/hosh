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
require_once 'Zend/Form/Decorator/Marker/File/Interface.php';
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
class Hosh_Form_Decorator_Dmuploader extends Zend_Form_Decorator_HtmlTag implements Zend_Form_Decorator_Marker_File_Interface
{
    /*
     * (non-PHPdoc) @see Zend_Form_Decorator_HtmlTag::render()
     */
    public function render ($content)
    {
        $isdebug = $this->getOption('isdebug');
        $xhtml = '<div id="dmuploader-' . $this->getOption('id') . '" class="dmuploader">
        <div class="text_dragdrop">' .
                 $this->getOption('text_dragdrop') . '</div>
        <div class="uploadfiles"></div>        
        <div class="or">-or-</div>
        <div class="browser">
        <label class="btn btn-default">
        <span>' .
                 $this->getOption('text_clickfilebrouser') . '</span>
        ' . $content . '
        </label>
        </div>';
        if ($isdebug) {
            $xhtml .= '<div class="debug"></div>';
        }
        $xhtml .= '</div>';
        
        return $xhtml;
    }
}