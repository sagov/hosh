<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Btn.php 21.04.2016 18:19:10
 */
require_once 'Hosh/Form/Decorator/Bootstrap/Inputgroup.php';

/**
 * Decorator form Bootstrap_Inputgroup_Btn
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Decorator
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Decorator_Bootstrap_Inputgroup_Btn extends Hosh_Form_Decorator_Bootstrap_Inputgroup
{
    /*
     * (non-PHPdoc) @see Zend_Form_Decorator_HtmlTag::render()
     */
    public function render ($content)
    {
        $text = $this->getOption('text');
        
        if (is_array($text)) {
            foreach ($text as $key=>$val) {
                $text[$key]['content'] = '<div class="input-group-btn">'.$text[$key]['content'].'</div>';
            }
        } else {
            $text = '<div class="input-group-btn">'.$text.'</div>';
        }
        $this->setOption('text', $text);
        return parent::render($content);
    }
}    