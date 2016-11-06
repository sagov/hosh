<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Multiselect.php 21.04.2016 18:23:41
 */
require_once 'Hosh/Form/Element/Jquery/Chosen.php';

/**
 * Form Element Helper
 *
 * @category Hosh
 * @package Hosh_Form
 * @subpackage Element
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Form_Element_Jquery_Chosen_Multiselect extends Hosh_Form_Element_Jquery_Chosen
{
    /**
     * 'multiple' attribute
     * @var string
     */
    public $multiple = 'multiple';
    
    /**
     * Use formSelect view helper by default
     * @var string
     */
    public $helper = 'formSelect';
    
    /**
     * Multiselect is an array of values by default
     * @var bool
     */
    protected $_isArray = true;
}