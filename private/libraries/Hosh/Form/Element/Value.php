<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Value.php 21.04.2016 18:23:41
 */
require_once 'Zend/Form/Element/Xhtml.php';

/**
 * Form Element Value
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Element
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Element_Value extends Zend_Form_Element_Xhtml
{

    /**
     *
     * @var unknown
     */
    public $helper = 'formNote';

    /**
     *
     * @param array $array            
     */
    public function addMultiOptions (array $array)
    {
        $this->addFilter('GetKeyFromArray', array(
                'MultiOptions' => $array
        ));
    }
}
